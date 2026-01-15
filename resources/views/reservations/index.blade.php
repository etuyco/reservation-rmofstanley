@extends('layouts.app')

@section('title', 'My Reservations - RM of Stanley')

@section('content')
<div class="page-header mb-4">
    <h1><i class="bi bi-calendar-event me-2"></i>My Reservations</h1>
    <p class="text-muted mb-0">View and manage your property reservations</p>
</div>

@forelse($reservations as $reservation)
    @php
        $duration = $reservation->start_time->diffInHours($reservation->end_time);
        $isPast = $reservation->end_time->isPast();
        $isToday = $reservation->start_time->isToday();
        $isUpcoming = $reservation->start_time->isFuture();
    @endphp
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1 fw-bold">{{ $reservation->property->name }}</h5>
                            <span class="badge" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white;">
                                {{ $reservation->property->type }}
                            </span>
                        </div>
                        @if($reservation->status === 'pending')
                            <span class="status-badge status-pending">
                                <i class="bi bi-clock"></i> Pending Approval
                            </span>
                        @elseif($reservation->status === 'approved')
                            <span class="status-badge status-approved">
                                <i class="bi bi-check-circle"></i> Approved
                            </span>
                        @elseif($reservation->status === 'rejected')
                            <span class="status-badge status-rejected">
                                <i class="bi bi-x-circle"></i> Rejected
                            </span>
                        @elseif($reservation->status === 'completed')
                            <span class="badge" style="background: #6b7280; color: white;">Completed</span>
                        @elseif($reservation->status === 'cancelled')
                            <span class="badge bg-dark">Cancelled</span>
                        @endif
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start p-3 rounded" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-left: 4px solid #3b82f6;">
                                <div class="me-3">
                                    <i class="bi bi-calendar-event" style="font-size: 1.5rem; color: #3b82f6;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">Start Time</small>
                                    <strong class="d-block">{{ $reservation->start_time->format('M d, Y') }}</strong>
                                    <span class="text-muted" style="font-size: 0.875rem;">
                                        <i class="bi bi-clock me-1"></i>{{ $reservation->start_time->format('h:i A') }}
                                    </span>
                                    @if($isToday)
                                        <span class="badge bg-info ms-2" style="font-size: 0.7rem;">Today</span>
                                    @elseif($isUpcoming)
                                        <span class="badge bg-success ms-2" style="font-size: 0.7rem;">Upcoming</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start p-3 rounded" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b;">
                                <div class="me-3">
                                    <i class="bi bi-calendar-x" style="font-size: 1.5rem; color: #f59e0b;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">End Time</small>
                                    <strong class="d-block">{{ $reservation->end_time->format('M d, Y') }}</strong>
                                    <span class="text-muted" style="font-size: 0.875rem;">
                                        <i class="bi bi-clock me-1"></i>{{ $reservation->end_time->format('h:i A') }}
                                    </span>
                                    @if($isPast)
                                        <span class="badge bg-secondary ms-2" style="font-size: 0.7rem;">Past</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center text-muted">
                                <i class="bi bi-hourglass-split me-2" style="font-size: 1.25rem;"></i>
                                <div>
                                    <small class="d-block" style="font-size: 0.75rem; opacity: 0.7;">Duration</small>
                                    <strong class="text-dark">{{ $duration }} {{ $duration == 1 ? 'hour' : 'hours' }}</strong>
                                </div>
                            </div>
                        </div>
                        @if($reservation->purpose)
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-info-circle me-2 mt-1" style="font-size: 1.25rem; color: #059669;"></i>
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 0.75rem; opacity: 0.7;">Purpose</small>
                                        <strong class="text-dark" style="font-size: 0.9rem;">{{ Str::limit($reservation->purpose, 40) }}</strong>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-primary">
                            <i class="bi bi-eye me-2"></i>View Details
                        </a>
                        @if($reservation->status === 'pending')
                            <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                    <i class="bi bi-x-circle me-2"></i>Cancel Reservation
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="card text-center py-5">
        <div class="card-body">
            <i class="bi bi-calendar-x" style="font-size: 4rem; color: #cbd5e0;"></i>
            <h4 class="mt-3 mb-2">No Reservations Yet</h4>
            <p class="text-muted mb-4">You don't have any reservations at the moment.</p>
            <a href="{{ route('properties.index') }}" class="btn btn-primary">
                <i class="bi bi-building me-2"></i>Browse Properties
            </a>
        </div>
    </div>
@endforelse
@endsection

