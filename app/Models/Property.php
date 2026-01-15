<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'location',
        'capacity',
        'price_per_hour',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_per_hour' => 'decimal:2',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the current status of the property
     * Returns: 'available', 'in_use', or 'reserved'
     */
    public function getCurrentStatusAttribute()
    {
        $now = Carbon::now();

        // Check for active bookings (approved and within time range)
        $activeBooking = $this->bookings()
            ->where('status', 'approved')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->first();

        if ($activeBooking) {
            return 'in_use';
        }

        // Check for active reservations (approved and within time range)
        $activeReservation = $this->reservations()
            ->where('status', 'approved')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->first();

        if ($activeReservation) {
            return 'reserved';
        }

        return 'available';
    }

    /**
     * Check if property is available for a given time range
     */
    public function isAvailableFor($startTime, $endTime, $excludeBookingId = null, $excludeReservationId = null)
    {
        $bookingQuery = $this->bookings()
            ->where('status', 'approved')
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            });

        if ($excludeBookingId) {
            $bookingQuery->where('id', '!=', $excludeBookingId);
        }

        if ($bookingQuery->exists()) {
            return false;
        }

        $reservationQuery = $this->reservations()
            ->where('status', 'approved')
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            });

        if ($excludeReservationId) {
            $reservationQuery->where('id', '!=', $excludeReservationId);
        }

        return !$reservationQuery->exists();
    }
}
