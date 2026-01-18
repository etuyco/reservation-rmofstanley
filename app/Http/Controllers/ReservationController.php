<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['create', 'store', 'checkAvailability', 'getCalendarAvailability']);
    }

    public function index()
    {
        $reservations = Auth::user()->reservations()->with('property')->latest()->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create(Property $property)
    {
        return view('reservations.create', compact('property'));
    }

    public function checkAvailability(Request $request, Property $property)
    {
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $isAvailable = $property->isAvailableFor($request->start_time, $request->end_time);
        
        $conflicts = collect();
        $suggestions = [];
        
        if (!$isAvailable) {
            $availabilityData = $this->getAvailabilityData($property, $request->start_time, $request->end_time);
            $conflicts = $availabilityData['conflicts'];
            $suggestions = $availabilityData['suggestions'];
        }

        return response()->json([
            'available' => $isAvailable,
            'conflicts' => $conflicts,
            'suggestions' => $suggestions,
            'message' => $isAvailable 
                ? 'Property is available for the selected time!'
                : 'Property is not available for the selected time.'
        ]);
    }

    public function getCalendarAvailability(Request $request, Property $property)
    {
        try {
            $date = $request->get('date', now()->format('Y-m-d'));
            $startDate = Carbon::parse($date)->startOfDay();
            $endDate = Carbon::parse($date)->endOfDay();

            \Log::info("Getting calendar availability", [
                'property_id' => $property->id,
                'property_name' => $property->name,
                'date' => $date
            ]);

            // Get all reservations for this property that overlap with the requested date
            $reservations = $property->reservations()
                ->where('status', 'approved')
                ->where(function($query) use ($startDate, $endDate) {
                    // Check for any overlap with the requested day
                    $query->where(function($overlap) use ($startDate, $endDate) {
                        $overlap->whereBetween('start_time', [$startDate, $endDate])
                               ->orWhereBetween('end_time', [$startDate, $endDate])
                               ->orWhere(function($contains) use ($startDate, $endDate) {
                                   $contains->where('start_time', '<=', $startDate)
                                           ->where('end_time', '>=', $endDate);
                               });
                    });
                })
                ->with('user')
                ->get();

            \Log::info("Found reservations", [
                'property_id' => $property->id,
                'reservations_count' => $reservations->count(),
                'reservations_details' => $reservations->map(function($res) {
                    return [
                        'id' => $res->id,
                        'start' => $res->start_time->format('Y-m-d H:i'),
                        'end' => $res->end_time->format('Y-m-d H:i'),
                        'type' => $res->booking_type
                    ];
                })
            ]);

            // Generate hourly time slots from 7 AM to 10 PM
            $timeSlots = [];
            $currentTime = $startDate->copy()->setTime(7, 0);
            $endTime = $startDate->copy()->setTime(22, 0);

            while ($currentTime <= $endTime) {
                $slotStart = $currentTime->copy();
                $slotEnd = $currentTime->copy()->addHour();
                
                $isAvailable = true;
                $conflictInfo = null;

                // Check if this hour conflicts with any reservations
                foreach ($reservations as $reservation) {
                    if ($slotStart < $reservation->end_time && $slotEnd > $reservation->start_time) {
                        $isAvailable = false;
                        $conflictInfo = [
                            'type' => $reservation->booking_type === 'daily' ? 'Daily Reservation' : 'Hourly Reservation',
                            'contact' => $reservation->guest_name ?: ($reservation->user ? $reservation->user->name : 'Guest'),
                            'purpose' => $reservation->purpose,
                            'start' => $reservation->start_time->format('M j, Y H:i'),
                            'end' => $reservation->end_time->format('M j, Y H:i')
                        ];
                        break;
                    }
                }

                $timeSlots[] = [
                    'time' => $currentTime->format('H:i'),
                    'display_time' => $currentTime->format('h:i A'),
                    'datetime' => $currentTime->format('Y-m-d\TH:i'),
                    'available' => $isAvailable,
                    'conflict' => $conflictInfo
                ];

                $currentTime->addHour();
            }

            return response()->json([
                'date' => $date,
                'slots' => $timeSlots,
                'property' => [
                    'id' => $property->id,
                    'name' => $property->name,
                    'price_per_hour' => $property->price_per_hour
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getCalendarAvailability: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load calendar data'], 500);
        }
    }

    private function suggestAlternativeTimes(Property $property, $startTime, $endTime)
    {
        $suggestions = [];
        $duration = Carbon::parse($startTime)->diffInHours(Carbon::parse($endTime));
        $baseDate = Carbon::parse($startTime);
        
        // Check next 7 days for available slots
        for ($i = 0; $i < 7; $i++) {
            $date = $baseDate->copy()->addDays($i);
            
            // Check morning slot (9 AM)
            $morningStart = $date->copy()->setTime(9, 0);
            $morningEnd = $morningStart->copy()->addHours($duration);
            if ($property->isAvailableFor($morningStart, $morningEnd)) {
                $suggestions[] = [
                    'start_time' => $morningStart->format('Y-m-d\TH:i'),
                    'end_time' => $morningEnd->format('Y-m-d\TH:i'),
                    'display' => $morningStart->format('M d, Y') . ' (Morning: 9:00 AM - ' . $morningEnd->format('h:i A') . ')',
                    'day_name' => $morningStart->format('l')
                ];
            }
            
            // Check afternoon slot (2 PM)
            $afternoonStart = $date->copy()->setTime(14, 0);
            $afternoonEnd = $afternoonStart->copy()->addHours($duration);
            if ($property->isAvailableFor($afternoonStart, $afternoonEnd)) {
                $suggestions[] = [
                    'start_time' => $afternoonStart->format('Y-m-d\TH:i'),
                    'end_time' => $afternoonEnd->format('Y-m-d\TH:i'),
                    'display' => $afternoonStart->format('M d, Y') . ' (Afternoon: 2:00 PM - ' . $afternoonEnd->format('h:i A') . ')',
                    'day_name' => $afternoonStart->format('l')
                ];
            }
            
            // Stop when we have 5 suggestions
            if (count($suggestions) >= 5) {
                break;
            }
        }
        
        return $suggestions;
    }

    public function store(Request $request, Property $property)
    {
        $validationRules = [
            'booking_type' => 'required|in:hourly,daily',
            'purpose' => 'nullable|string|max:1000',
        ];

        // Add specific validation rules based on booking type
        if ($request->booking_type === 'hourly') {
            $validationRules = array_merge($validationRules, [
                'start_time' => 'required|date|after_or_equal:' . now()->format('Y-m-d H:i'),
                'end_time' => 'required|date|after:start_time',
            ]);
        } else if ($request->booking_type === 'daily') {
            $validationRules = array_merge($validationRules, [
                'selected_days' => 'required|json',
            ]);
        }

        // Add guest validation rules if user is not authenticated
        if (!Auth::check()) {
            $validationRules = array_merge($validationRules, [
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|email|max:255',
                'guest_phone' => 'required|string|max:20',
                'guest_organization' => 'nullable|string|max:255',
            ]);
        }

        // Debug: Log the request data
        Log::info('Reservation request data:', $request->all());

        try {
            $request->validate($validationRules);
            Log::info('Validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            throw $e;
        }

        // Handle daily bookings
        if ($request->booking_type === 'daily') {
            return $this->storeDailyBooking($request, $property);
        }

        // Handle hourly bookings (existing logic)
        return $this->storeHourlyBooking($request, $property);
    }

    private function storeHourlyBooking(Request $request, Property $property)
    {
        // Check if property is available
        if (!$property->isAvailableFor($request->start_time, $request->end_time)) {
            // Get detailed conflict information and suggestions
            $availabilityData = $this->getAvailabilityData($property, $request->start_time, $request->end_time);
            
            return back()
                ->withErrors([
                    'availability' => 'Property is not available for the selected time.',
                ])
                ->with([
                    'conflicts' => $availabilityData['conflicts'],
                    'suggestions' => $availabilityData['suggestions'],
                    'show_availability_error' => true
                ])
                ->withInput();
        }

        $reservationData = [
            'property_id' => $property->id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'purpose' => $request->purpose,
            'status' => 'pending',
            'booking_type' => 'hourly',
        ];

        // Handle authenticated vs guest users
        if (Auth::check()) {
            $reservationData['user_id'] = Auth::id();
        } else {
            // For guest reservations, store contact information
            $reservationData = array_merge($reservationData, [
                'guest_name' => $request->guest_name,
                'guest_email' => $request->guest_email,
                'guest_phone' => $request->guest_phone,
                'guest_organization' => $request->guest_organization,
            ]);
        }

        Log::info('Creating hourly reservation with data:', $reservationData);
        
        $reservation = Reservation::create($reservationData);
        
        Log::info('Hourly reservation created:', ['id' => $reservation->id, 'created' => true]);

        if (Auth::check()) {
            return redirect()->route('reservations.index')->with('success', 'Hourly reservation request submitted successfully. Waiting for admin approval.');
        } else {
            return redirect()->route('properties.show', $property)->with('success', 'Hourly reservation request submitted successfully! We will contact you at ' . $request->guest_email . ' regarding your reservation.');
        }
    }

    private function storeDailyBooking(Request $request, Property $property)
    {
        $selectedDays = json_decode($request->selected_days, true);
        
        if (!is_array($selectedDays) || count($selectedDays) === 0) {
            return back()->withErrors([
                'selected_days' => 'You must select at least one day for daily booking.',
            ])->withInput();
        }

        if (count($selectedDays) > $property->max_daily_booking_days) {
            return back()->withErrors([
                'selected_days' => "You can select up to {$property->max_daily_booking_days} days for daily booking.",
            ])->withInput();
        }

        // Sort the selected days to get proper start and end dates
        sort($selectedDays);

        // Validate that all selected days are in the future
        $now = now();
        foreach ($selectedDays as $dayStr) {
            $dayDate = Carbon::parse($dayStr);
            if ($dayDate->isBefore($now->startOfDay())) {
                return back()->withErrors([
                    'selected_days' => 'Cannot book dates in the past.',
                ])->withInput();
            }
        }

        // Check availability for each day (full day booking: 8 AM to 6 PM)
        $conflicts = [];
        foreach ($selectedDays as $dayStr) {
            $dayStart = Carbon::parse($dayStr)->setHour(8)->setMinute(0);
            $dayEnd = Carbon::parse($dayStr)->setHour(18)->setMinute(0);
            
            if (!$property->isAvailableFor($dayStart, $dayEnd)) {
                $conflicts[] = $dayStart->format('M d, Y');
            }
        }

        if (!empty($conflicts)) {
            $conflictDates = implode(', ', $conflicts);
            return back()->withErrors([
                'availability' => "Property is not available for the following days: {$conflictDates}",
            ])->withInput();
        }

        // Create a single reservation covering the date range
        $startDate = Carbon::parse($selectedDays[0])->setHour(8)->setMinute(0);
        $endDate = Carbon::parse(end($selectedDays))->setHour(18)->setMinute(0);
        
        $reservationData = [
            'property_id' => $property->id,
            'start_time' => $startDate,
            'end_time' => $endDate,
            'purpose' => $request->purpose,
            'status' => 'pending',
            'booking_type' => 'daily',
        ];

        // Handle authenticated vs guest users
        if (Auth::check()) {
            $reservationData['user_id'] = Auth::id();
        } else {
            $reservationData = array_merge($reservationData, [
                'guest_name' => $request->guest_name,
                'guest_email' => $request->guest_email,
                'guest_phone' => $request->guest_phone,
                'guest_organization' => $request->guest_organization,
            ]);
        }

        $reservation = Reservation::create($reservationData);
        
        Log::info('Daily reservation created as date range:', [
            'id' => $reservation->id, 
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'selected_days' => $selectedDays,
            'days_count' => count($selectedDays)
        ]);

        $daysCount = count($selectedDays);
        $daysText = $daysCount === 1 ? 'day' : 'days';

        if (Auth::check()) {
            return redirect()->route('reservations.index')->with('success', "Daily reservation request for {$daysCount} {$daysText} submitted successfully. Waiting for admin approval.");
        } else {
            return redirect()->route('properties.show', $property)->with('success', "Daily reservation request for {$daysCount} {$daysText} submitted successfully! We will contact you at " . $request->guest_email . " regarding your reservation.");
        }
    }

    private function getAvailabilityData($property, $startTime, $endTime)
    {
        $conflictingReservations = $property->reservations()
            ->where('status', 'approved')
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            })
            ->with(['user'])
            ->get()
            ->map(function($reservation) {
                return [
                    'type' => 'reservation',
                    'start_time' => $reservation->start_time->format('M d, Y h:i A'),
                    'end_time' => $reservation->end_time->format('M d, Y h:i A'),
                    'contact' => $reservation->isGuestReservation() 
                        ? $reservation->guest_name 
                        : $reservation->user->name,
                    'purpose' => $reservation->purpose
                ];
            });

        $conflictingBookings = $property->bookings()
            ->where('status', 'approved')
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            })
            ->with(['user'])
            ->get()
            ->map(function($booking) {
                return [
                    'type' => 'booking',
                    'start_time' => $booking->start_time->format('M d, Y h:i A'),
                    'end_time' => $booking->end_time->format('M d, Y h:i A'),
                    'contact' => $booking->user->name,
                    'purpose' => $booking->purpose
                ];
            });

        $suggestions = $this->suggestAlternativeTimes($property, $startTime, $endTime);

        return [
            'conflicts' => $conflictingReservations->merge($conflictingBookings),
            'suggestions' => $suggestions
        ];
    }

    public function show(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $reservation->load('property', 'user');
        return view('reservations.show', compact('reservation'));
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reservation->status === 'approved') {
            return back()->withErrors(['error' => 'Cannot cancel an approved reservation. Please contact admin.']);
        }

        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reservation cancelled successfully.');
    }
}
