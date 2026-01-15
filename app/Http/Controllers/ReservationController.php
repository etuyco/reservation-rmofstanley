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
        $date = $request->get('date', now()->format('Y-m-d'));
        $startDate = Carbon::parse($date)->startOfDay();
        $endDate = Carbon::parse($date)->endOfDay();

        \Log::info("Getting calendar availability", [
            'property_id' => $property->id,
            'property_name' => $property->name,
            'date' => $date
        ]);

        // Get all reservations and bookings for this property and date
        $reservations = $property->reservations()
            ->where('status', 'approved')
            ->where(function($query) use ($date, $startDate, $endDate) {
                $query->whereDate('start_time', $date)
                      ->orWhereDate('end_time', $date)
                      ->orWhere(function($subQuery) use ($startDate, $endDate) {
                          $subQuery->where('start_time', '<=', $startDate)
                                   ->where('end_time', '>=', $endDate);
                      });
            })
            ->with('user')
            ->get();

        $bookings = $property->bookings()
            ->where('status', 'approved')
            ->where(function($query) use ($date, $startDate, $endDate) {
                $query->whereDate('start_time', $date)
                      ->orWhereDate('end_time', $date)
                      ->orWhere(function($subQuery) use ($startDate, $endDate) {
                          $subQuery->where('start_time', '<=', $startDate)
                                   ->where('end_time', '>=', $endDate);
                      });
            })
            ->with('user')
            ->get();

        \Log::info("Found reservations/bookings", [
            'property_id' => $property->id,
            'reservations_count' => $reservations->count(),
            'bookings_count' => $bookings->count(),
            'reservations' => $reservations->map(function($r) {
                return [
                    'id' => $r->id,
                    'start_time' => $r->start_time,
                    'end_time' => $r->end_time,
                    'property_id' => $r->property_id
                ];
            }),
            'bookings' => $bookings->map(function($b) {
                return [
                    'id' => $b->id,
                    'start_time' => $b->start_time,
                    'end_time' => $b->end_time,
                    'property_id' => $b->property_id
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
                        'type' => 'reservation',
                        'contact' => $reservation->contact_name ?: ($reservation->user ? $reservation->user->name : 'Guest'),
                        'purpose' => $reservation->purpose,
                        'start' => $reservation->start_time->format('H:i'),
                        'end' => $reservation->end_time->format('H:i')
                    ];
                    break;
                }
            }

            // Check if this hour conflicts with any bookings
            if ($isAvailable) {
                foreach ($bookings as $booking) {
                    if ($slotStart < $booking->end_time && $slotEnd > $booking->start_time) {
                        $isAvailable = false;
                        $conflictInfo = [
                            'type' => 'booking',
                            'contact' => $booking->user ? $booking->user->name : 'Guest',
                            'purpose' => $booking->purpose,
                            'start' => $booking->start_time->format('H:i'),
                            'end' => $booking->end_time->format('H:i')
                        ];
                        break;
                    }
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
            'start_time' => 'required|date|after_or_equal:' . now()->format('Y-m-d H:i'),
            'end_time' => 'required|date|after:start_time',
            'purpose' => 'nullable|string|max:1000',
        ];

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

        Log::info('Creating reservation with data:', $reservationData);
        
        $reservation = Reservation::create($reservationData);
        
        Log::info('Reservation created:', ['id' => $reservation->id, 'created' => true]);

        if (Auth::check()) {
            return redirect()->route('reservations.index')->with('success', 'Reservation request submitted successfully. Waiting for admin approval.');
        } else {
            return redirect()->route('properties.show', $property)->with('success', 'Reservation request submitted successfully! We will contact you at ' . $request->guest_email . ' regarding your reservation.');
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
