@extends('layouts.app')

@section('title', 'Booking Details - RM of Stanley')

@section('content')
<div class="row mb-4">
    <div class="col">
        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to My Bookings
        </a>
        <h1>Booking Details</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $booking->property->name }}</h5>
                <p class="card-text">
                    <strong>Type:</strong> {{ $booking->property->type }}<br>
                    <strong>Start Time:</strong> {{ $booking->start_time->format('M d, Y h:i A') }}<br>
                    <strong>End Time:</strong> {{ $booking->end_time->format('M d, Y h:i A') }}<br>
                    @if($booking->purpose)
                        <strong>Purpose:</strong> {{ $booking->purpose }}<br>
                    @endif
                    <strong>Status:</strong> 
                    @if($booking->status === 'pending')
                        <span class="status-badge status-pending">
                            <i class="bi bi-clock"></i> Pending Approval
                        </span>
                    @elseif($booking->status === 'approved')
                        <span class="status-badge status-approved">
                            <i class="bi bi-check-circle"></i> Approved
                        </span>
                    @elseif($booking->status === 'rejected')
                        <span class="status-badge status-rejected">
                            <i class="bi bi-x-circle"></i> Rejected
                        </span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                    @endif
                </p>
                @if($booking->admin_notes)
                    <div class="alert alert-info">
                        <strong>Admin Notes:</strong> {{ $booking->admin_notes }}
                    </div>
                @endif
                <p class="text-muted">
                    <small>Created: {{ $booking->created_at->format('M d, Y h:i A') }}</small>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

