<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'start_time',
        'end_time',
        'status',
        'booking_type',
        'purpose',
        'admin_notes',
        'guest_name',
        'guest_email',
        'guest_phone',
        'guest_organization',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isGuestReservation()
    {
        return is_null($this->user_id) && !empty($this->guest_name);
    }

    public function getReservationContactAttribute()
    {
        if ($this->isGuestReservation()) {
            return $this->guest_name . ' (' . $this->guest_email . ')';
        }
        
        return $this->user ? $this->user->name . ' (' . $this->user->email . ')' : 'Unknown';
    }
}
