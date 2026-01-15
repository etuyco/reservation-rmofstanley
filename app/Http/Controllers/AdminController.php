<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Reservation;
use App\Models\Property;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard()
    {
        $pendingBookings = Booking::where('status', 'pending')->with(['user', 'property'])->latest()->get();
        $pendingReservations = Reservation::where('status', 'pending')->with(['user', 'property'])->latest()->get();
        $properties = Property::all();
        
        return view('admin.dashboard', compact('pendingBookings', 'pendingReservations', 'properties'));
    }

    public function approveBooking(Booking $booking)
    {
        // Check if property is still available
        if (!$booking->property->isAvailableFor($booking->start_time, $booking->end_time, $booking->id)) {
            return back()->withErrors(['error' => 'Property is no longer available for this time slot.']);
        }

        $booking->update(['status' => 'approved']);
        return back()->with('success', 'Booking approved successfully.');
    }

    public function rejectBooking(Booking $booking, Request $request)
    {
        $booking->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);
        return back()->with('success', 'Booking rejected.');
    }

    public function approveReservation(Reservation $reservation)
    {
        // Check if property is still available
        if (!$reservation->property->isAvailableFor($reservation->start_time, $reservation->end_time, null, $reservation->id)) {
            return back()->withErrors(['error' => 'Property is no longer available for this time slot.']);
        }

        $reservation->update(['status' => 'approved']);

        $contactName = $reservation->isGuestReservation() 
            ? $reservation->guest_name 
            : ($reservation->user->name ?? 'the user');

        return back()->with('success', 'Reservation approved successfully for ' . $contactName . '.');
    }

    public function rejectReservation(Reservation $reservation, Request $request)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $reservation->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        $contactName = $reservation->isGuestReservation() 
            ? $reservation->guest_name 
            : ($reservation->user->name ?? 'the user');

        return back()->with('success', 'Reservation rejected successfully. Rejection reason has been recorded for ' . $contactName . '.');
    }

    public function allBookings()
    {
        $bookings = Booking::with(['user', 'property'])->latest()->paginate(15);
        return view('admin.bookings', compact('bookings'));
    }

    public function allReservations()
    {
        $reservations = Reservation::with(['user', 'property'])->latest()->paginate(15);
        return view('admin.reservations', compact('reservations'));
    }

    public function updateReservation(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $reservation->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        $contactName = $reservation->isGuestReservation() 
            ? $reservation->guest_name 
            : ($reservation->user->name ?? 'the user');

        return back()->with('success', 'Reservation status updated successfully for ' . $contactName . '.');
    }

    public function destroyReservation(Reservation $reservation)
    {
        $contactName = $reservation->isGuestReservation() 
            ? $reservation->guest_name 
            : ($reservation->user->name ?? 'the user');

        $propertyName = $reservation->property->name;
        $reservation->delete();

        return back()->with('success', 'Reservation for ' . $contactName . ' at ' . $propertyName . ' has been deleted successfully.');
    }
}
