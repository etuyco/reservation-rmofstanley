@extends('layouts.app')

@section('title', 'My Bookings - RM of Stanley')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1><i class="bi bi-calendar-check"></i> My Bookings</h1>
    </div>
</div>

@forelse($bookings as $booking)
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="card-title">{{ $booking->property->name }}</h5>
                    <p class="card-text">
                        <strong>Type:</strong> {{ $booking->property->type }}<br>
                        <strong>Start:</strong> {{ $booking->start_time->format('M d, Y h:i A') }}<br>
                        <strong>End:</strong> {{ $booking->end_time->format('M d, Y h:i A') }}<br>
                        @if($booking->purpose)
                            <strong>Purpose:</strong> {{ $booking->purpose }}
                        @endif
                    </p>
                </div>
                <div class="col-md-4 text-end">
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
                    @elseif($booking->status === 'completed')
                        <span class="badge bg-secondary">Completed</span>
                    @elseif($booking->status === 'cancelled')
                        <span class="badge bg-dark">Cancelled</span>
                    @endif
                    <div class="mt-3">
                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
                        @if($booking->status === 'pending')
                            <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="d-inline mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                    Cancel
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> You don't have any bookings yet.
        <a href="{{ route('home') }}" class="alert-link">Browse properties</a> to make a booking.
    </div>
@endforelse
@endsection

