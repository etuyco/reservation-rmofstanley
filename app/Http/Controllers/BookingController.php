<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $bookings = Auth::user()->bookings()->with('property')->latest()->get();
        return view('bookings.index', compact('bookings'));
    }

    public function create(Property $property)
    {
        return view('bookings.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        $request->validate([
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'purpose' => 'nullable|string|max:1000',
        ]);

        // Check if property is available
        if (!$property->isAvailableFor($request->start_time, $request->end_time)) {
            return back()->withErrors(['error' => 'Property is not available for the selected time.'])->withInput();
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'property_id' => $property->id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking request submitted successfully. Waiting for admin approval.');
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $booking->load('property', 'user');
        return view('bookings.show', compact('booking'));
    }

    public function destroy(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status === 'approved') {
            return back()->withErrors(['error' => 'Cannot cancel an approved booking. Please contact admin.']);
        }

        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking cancelled successfully.');
    }
}
