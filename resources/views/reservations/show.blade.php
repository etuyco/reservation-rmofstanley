@extends('layouts.app')

@section('title', 'Reservation Details - RM of Stanley')

@section('content')
<div class="row mb-4">
    <div class="col">
        @if(auth()->check() && auth()->user()->isAdmin())
            <a href="{{ route('admin.reservations') }}" class="btn btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Back to All Reservations
            </a>
        @else
            <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Back to My Reservations
            </a>
        @endif
        <h1>Reservation Details</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ $reservation->property->name }}</h5>
                <p class="card-text">
                    <strong>Property Type:</strong> <span class="badge bg-secondary">{{ $reservation->property->type }}</span><br>
                    @if($reservation->property->location)
                        <strong>Location:</strong> <i class="bi bi-geo-alt"></i> {{ $reservation->property->location }}<br>
                    @endif
                    <strong>Start Time:</strong> {{ $reservation->start_time->format('M d, Y h:i A') }}<br>
                    <strong>End Time:</strong> {{ $reservation->end_time->format('M d, Y h:i A') }}<br>
                    @php
                        $duration = $reservation->start_time->diffInHours($reservation->end_time);
                    @endphp
                    <strong>Duration:</strong> {{ $duration }} {{ $duration == 1 ? 'hour' : 'hours' }}<br>
                    @if($reservation->purpose)
                        <strong>Purpose:</strong> {{ $reservation->purpose }}<br>
                    @endif
                    <strong>Status:</strong> 
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
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($reservation->status) }}</span>
                    @endif
                </p>
            </div>
        </div>

        @if(auth()->check() && auth()->user()->isAdmin())
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person"></i> User Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Name:</strong> {{ $reservation->user->name }}<br>
                        <strong>Email:</strong> {{ $reservation->user->email }}
                    </p>
                </div>
            </div>
        @endif

        @if($reservation->admin_notes)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Admin Notes</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $reservation->admin_notes }}</p>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-0">
                    <small>
                        <strong>Created:</strong> {{ $reservation->created_at->format('M d, Y h:i A') }}<br>
                        <strong>Last Updated:</strong> {{ $reservation->updated_at->format('M d, Y h:i A') }}
                    </small>
                </p>
            </div>
        </div>
    </div>

    @if(auth()->check() && auth()->user()->isAdmin() && $reservation->status === 'pending')
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Admin Actions</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reservations.approve', $reservation) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle"></i> Approve Reservation
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="bi bi-x-circle"></i> Reject Reservation
                    </button>
                    
                    <!-- Reject Modal -->
                    <div class="modal fade" id="rejectModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.reservations.reject', $reservation) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reject Reservation</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="admin_notes" class="form-label">Reason (optional)</label>
                                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Reject</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

