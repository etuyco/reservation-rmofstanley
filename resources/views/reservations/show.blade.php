@extends('layouts.app')

@section('title', 'Reservation Details - RM of Stanley')

@section('styles')
<style>
.reservation-detail-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.reservation-detail-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 4s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.1; }
    50% { transform: scale(1.05); opacity: 0.2; }
}

.detail-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    transition: all 0.3s ease;
    overflow: hidden;
    margin-bottom: 1.5rem;
    background: white;
}

.detail-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.detail-card .card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: none;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.detail-card .card-header h5 {
    margin: 0;
    font-weight: 600;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-card .card-body {
    padding: 1.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
}

.info-item:hover {
    background: #f1f5f9;
    transform: translateX(4px);
}

.info-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.2rem;
    color: white;
    flex-shrink: 0;
}

.info-icon.property { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
.info-icon.time { background: linear-gradient(135deg, #10b981 0%, #047857 100%); }
.info-icon.duration { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
.info-icon.purpose { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
.info-icon.status { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
.info-icon.location { background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); }
.info-icon.guests { background: linear-gradient(135deg, #84cc16 0%, #65a30d 100%); }

.info-content h6 {
    margin: 0 0 0.25rem 0;
    color: #64748b;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.info-content .value {
    color: #1e293b;
    font-weight: 600;
    font-size: 1rem;
}

.status-badge {
    border-radius: 25px;
    padding: 0.6rem 1.2rem;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.status-pending {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    border: 1px solid #f59e0b;
}

.status-approved {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #047857;
    border: 1px solid #10b981;
}

.status-rejected {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
    border: 1px solid #ef4444;
}

.admin-actions {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border: 1px solid #f87171;
}

.action-btn {
    border-radius: 12px;
    padding: 0.875rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    position: relative;
    overflow: hidden;
}

.action-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.6s;
}

.action-btn:hover::before {
    left: 100%;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.btn-approve {
    background: linear-gradient(135deg, #10b981 0%, #047857 100%);
    color: white;
}

.btn-reject {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.timeline-item {
    padding: 1rem;
    background: white;
    border-left: 3px solid #e2e8f0;
    margin-left: 1rem;
    position: relative;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 1.5rem;
    width: 12px;
    height: 12px;
    background: #3b82f6;
    border-radius: 50%;
    border: 3px solid white;
}

.back-btn {
    background: white;
    border: 1px solid #e2e8f0;
    color: #64748b;
    border-radius: 12px;
    padding: 0.75rem 1.25rem;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

.back-btn:hover {
    background: #f8fafc;
    color: #1e293b;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-decoration: none;
}

.property-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 1rem;
}

.admin-user-card {
    background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
    border: 1px solid #c4b5fd;
}

.admin-notes-card {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: 1px solid #f59e0b;
}

@media (max-width: 768px) {
    .reservation-detail-header {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .info-item {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .info-icon {
        margin: 0;
    }
}
</style>
@endsection

@section('content')
<!-- Back Button -->
@if(auth()->check() && auth()->user()->isAdmin())
    <a href="{{ route('admin.reservations') }}" class="back-btn mb-3">
        <i class="bi bi-arrow-left"></i> Back to All Reservations
    </a>
@else
    <a href="{{ route('reservations.index') }}" class="back-btn mb-3">
        <i class="bi bi-arrow-left"></i> Back to My Reservations
    </a>
@endif

<!-- Header Section -->
<div class="reservation-detail-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="mb-2">
                <i class="bi bi-calendar-check me-3"></i>{{ $reservation->property->name }}
            </h1>
            <p class="mb-0 opacity-90">Reservation #{{ $reservation->id }} - Created {{ $reservation->created_at->format('M d, Y') }}</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
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
                <span class="status-badge" style="background: #f1f5f9; color: #64748b;">
                    {{ ucfirst($reservation->status) }}
                </span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <!-- Property Details Card -->
        <div class="detail-card">
            <div class="card-header">
                <h5><i class="bi bi-building"></i>Property Information</h5>
            </div>
            <div class="card-body">
                @if($reservation->property->image)
                    <img src="{{ $reservation->property->image }}" alt="{{ $reservation->property->name }}" class="property-image">
                @endif
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-icon property">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="info-content">
                                <h6>Property Type</h6>
                                <div class="value">{{ $reservation->property->type }}</div>
                            </div>
                        </div>
                    </div>
                    
                    @if($reservation->property->location)
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-icon location">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div class="info-content">
                                <h6>Location</h6>
                                <div class="value">{{ $reservation->property->location }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($reservation->guest_count)
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-icon guests">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="info-content">
                                <h6>Guest Count</h6>
                                <div class="value">{{ $reservation->guest_count }} people</div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($reservation->purpose)
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-icon purpose">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <div class="info-content">
                                <h6>Purpose</h6>
                                <div class="value">{{ $reservation->purpose }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Time & Duration Card -->
        <div class="detail-card">
            <div class="card-header">
                <h5><i class="bi bi-clock"></i>Schedule Information</h5>
            </div>
            <div class="card-body">
                @php
                    $duration = $reservation->start_time->diffInHours($reservation->end_time);
                @endphp
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="info-item">
                            <div class="info-icon time">
                                <i class="bi bi-play-circle"></i>
                            </div>
                            <div class="info-content">
                                <h6>Start Time</h6>
                                <div class="value">{{ $reservation->start_time->format('M d, Y') }}</div>
                                <div class="text-muted small">{{ $reservation->start_time->format('h:i A') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="info-item">
                            <div class="info-icon time">
                                <i class="bi bi-stop-circle"></i>
                            </div>
                            <div class="info-content">
                                <h6>End Time</h6>
                                <div class="value">{{ $reservation->end_time->format('M d, Y') }}</div>
                                <div class="text-muted small">{{ $reservation->end_time->format('h:i A') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="info-item">
                            <div class="info-icon duration">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <div class="info-content">
                                <h6>Duration</h6>
                                <div class="value">{{ $duration }} {{ $duration == 1 ? 'hour' : 'hours' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Notes Card -->
        @if($reservation->admin_notes)
            <div class="detail-card admin-notes-card">
                <div class="card-header">
                    <h5><i class="bi bi-sticky"></i>Admin Notes</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $reservation->admin_notes }}</p>
                </div>
            </div>
        @endif

        <!-- System Information -->
        <div class="detail-card">
            <div class="card-header">
                <h5><i class="bi bi-info-circle"></i>System Information</h5>
            </div>
            <div class="card-body">
                <div class="timeline-item">
                    <strong>Created:</strong> {{ $reservation->created_at->format('M d, Y h:i A') }}
                </div>
                <div class="timeline-item">
                    <strong>Last Updated:</strong> {{ $reservation->updated_at->format('M d, Y h:i A') }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <!-- User Information (Admin Only) -->
        @if(auth()->check() && auth()->user()->isAdmin())
            <div class="detail-card admin-user-card">
                <div class="card-header">
                    <h5><i class="bi bi-person-circle"></i>User Information</h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <div class="info-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="info-content">
                            <h6>Full Name</h6>
                            <div class="value">{{ $reservation->user->name }}</div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="info-content">
                            <h6>Email Address</h6>
                            <div class="value">{{ $reservation->user->email }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Admin Actions -->
        @if(auth()->check() && auth()->user()->isAdmin() && $reservation->status === 'pending')
            <div class="detail-card">
                <div class="card-header">
                    <h5><i class="bi bi-gear"></i>Admin Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <form action="{{ route('admin.reservations.approve', $reservation) }}" method="POST">
                            @csrf
                            <button type="submit" class="action-btn btn-approve w-100">
                                <i class="bi bi-check-circle me-2"></i>Approve Reservation
                            </button>
                        </form>
                        
                        <button type="button" class="action-btn btn-reject w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle me-2"></i>Reject Reservation
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Stats -->
        <div class="detail-card">
            <div class="card-header">
                <h5><i class="bi bi-graph-up"></i>Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded-3">
                            <div class="h4 text-primary mb-1">{{ $duration }}</div>
                            <small class="text-muted">Hours</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded-3">
                            @php
                                $daysAhead = now()->diffInDays($reservation->start_time, false);
                            @endphp
                            <div class="h4 text-info mb-1">{{ $daysAhead > 0 ? $daysAhead : 0 }}</div>
                            <small class="text-muted">Days Ahead</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if(auth()->check() && auth()->user()->isAdmin() && $reservation->status === 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.reservations.reject', $reservation) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>Reject Reservation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle me-2"></i>
                        This action will reject the reservation and notify the user.
                    </div>
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Reason for Rejection</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="4" placeholder="Please provide a reason for rejecting this reservation..."></textarea>
                        <div class="form-text">This message will be visible to the user.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-check me-2"></i>Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

