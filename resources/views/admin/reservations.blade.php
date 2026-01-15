@extends('layouts.app')

@section('title', 'Manage Reservations - RM of Stanley')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap">
        <div>
            <h1><i class="bi bi-calendar-check me-2"></i>Manage Reservations</h1>
            <p class="text-muted mb-0">View and manage all property reservations</p>
        </div>
        <div class="mt-2 mt-md-0">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.location.reload()">
                    <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                </button>
                <a href="{{ route('properties.index') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-building me-1"></i> Manage Properties
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>All Reservations</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Property</th>
                                <th>Guest/User</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservations as $reservation)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary">#{{ $reservation->id }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-semibold">{{ $reservation->property->name }}</div>
                                            <small class="text-muted">{{ $reservation->property->location }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($reservation->isGuestReservation())
                                            <div class="fw-semibold text-info">
                                                <i class="bi bi-person-plus me-1"></i>Guest
                                            </div>
                                            <small class="text-muted">{{ $reservation->guest_name }}</small><br>
                                            <small class="text-muted">{{ $reservation->guest_email }}</small>
                                        @else
                                            <div class="fw-semibold">
                                                <i class="bi bi-person me-1"></i>{{ $reservation->user->name }}
                                            </div>
                                            <small class="text-muted">{{ $reservation->user->email }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-success">{{ $reservation->start_time->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $reservation->start_time->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-danger">{{ $reservation->end_time->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $reservation->end_time->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @switch($reservation->status)
                                            @case('pending')
                                                <span class="badge" style="background: #f59e0b; color: white; padding: 0.375rem 0.75rem;">
                                                    <i class="bi bi-clock"></i> Pending
                                                </span>
                                                @break
                                            @case('approved')
                                                <span class="status-badge status-available" style="padding: 0.375rem 0.75rem;">
                                                    <i class="bi bi-check-circle"></i> Approved
                                                </span>
                                                @break
                                            @case('rejected')
                                                <span class="badge" style="background: #dc2626; color: white; padding: 0.375rem 0.75rem;">
                                                    <i class="bi bi-x-circle"></i> Rejected
                                                </span>
                                                @break
                                            @default
                                                <span class="badge" style="background: #6b7280; color: white; padding: 0.375rem 0.75rem;">
                                                    {{ ucfirst($reservation->status) }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if($reservation->status === 'pending')
                                                <form action="{{ route('admin.reservations.approve', $reservation->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-success" title="Approve">
                                                        <i class="bi bi-check"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $reservation->id }}" title="Reject">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $reservation->id }}" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @if($reservation->status !== 'pending')
                                                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $reservation->id }}" title="Edit Status">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            @endif
                                            <form action="{{ route('admin.reservations.destroy', $reservation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this reservation? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        No reservations found. Check back later for new reservations.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($reservations->hasPages())
                <div class="card-footer">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals for each reservation -->
@foreach($reservations as $reservation)
    <!-- View Details Modal -->
    <div class="modal fade" id="detailsModal{{ $reservation->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $reservation->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="detailsModalLabel{{ $reservation->id }}">
                        <i class="bi bi-info-circle me-2"></i>Reservation Details #{{ $reservation->id }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">Property Information</h6>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Property Name:</label>
                                <p class="mb-1">{{ $reservation->property->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Location:</label>
                                <p class="mb-1">{{ $reservation->property->location }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">Reservation Details</h6>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Start Date & Time:</label>
                                <p class="mb-1">{{ $reservation->start_time->format('l, F j, Y \a\t g:i A') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">End Date & Time:</label>
                                <p class="mb-1">{{ $reservation->end_time->format('l, F j, Y \a\t g:i A') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Duration:</label>
                                <p class="mb-1">{{ $reservation->start_time->diffForHumans($reservation->end_time, true) }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status:</label>
                                <p class="mb-1">
                                    @switch($reservation->status)
                                        @case('pending')
                                            <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending</span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-success"><i class="bi bi-check me-1"></i>Approved</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger"><i class="bi bi-x me-1"></i>Rejected</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($reservation->status) }}</span>
                                    @endswitch
                                </p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-bold text-primary mb-3">Contact Information</h6>
                            @if($reservation->isGuestReservation())
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Type:</label>
                                        <p class="mb-1"><i class="bi bi-person-plus text-info me-1"></i>Guest Reservation</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Guest Name:</label>
                                        <p class="mb-1">{{ $reservation->guest_name }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Email:</label>
                                        <p class="mb-1">{{ $reservation->guest_email }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Phone:</label>
                                        <p class="mb-1">{{ $reservation->guest_phone }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Type:</label>
                                        <p class="mb-1"><i class="bi bi-person text-primary me-1"></i>Registered User</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">User Name:</label>
                                        <p class="mb-1">{{ $reservation->user->name }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Email:</label>
                                        <p class="mb-1">{{ $reservation->user->email }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($reservation->admin_notes)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-bold text-danger mb-3">Admin Notes</h6>
                                <div class="alert alert-light border">
                                    <p class="mb-0">{{ $reservation->admin_notes }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-bold text-muted mb-3">System Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Created:</label>
                                    <p class="mb-1">{{ $reservation->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Last Updated:</label>
                                    <p class="mb-1">{{ $reservation->updated_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Close
                    </button>
                    @if($reservation->status === 'pending')
                        <form action="{{ route('admin.reservations.approve', $reservation->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check me-1"></i>Approve
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $reservation->id }}">
                            <i class="bi bi-x me-1"></i>Reject
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Status Modal -->
    <div class="modal fade" id="editModal{{ $reservation->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $reservation->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editModalLabel{{ $reservation->id }}">
                        <i class="bi bi-pencil me-2"></i>Edit Reservation #{{ $reservation->id }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.reservations.update', $reservation->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary">Reservation Summary</h6>
                            <p class="text-muted mb-3">
                                <strong>Property:</strong> {{ $reservation->property->name }}<br>
                                <strong>Contact:</strong> 
                                @if($reservation->isGuestReservation())
                                    {{ $reservation->guest_name }} (Guest)
                                @else
                                    {{ $reservation->user->name }} (User)
                                @endif
                                <br>
                                <strong>Dates:</strong> {{ $reservation->start_time->format('M d, Y') }} - {{ $reservation->end_time->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="mb-3">
                            <label for="status{{ $reservation->id }}" class="form-label fw-semibold">Reservation Status</label>
                            <select class="form-select" id="status{{ $reservation->id }}" name="status" required>
                                <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }}>
                                    <i class="bi bi-clock"></i> Pending
                                </option>
                                <option value="approved" {{ $reservation->status == 'approved' ? 'selected' : '' }}>
                                    <i class="bi bi-check"></i> Approved
                                </option>
                                <option value="rejected" {{ $reservation->status == 'rejected' ? 'selected' : '' }}>
                                    <i class="bi bi-x"></i> Rejected
                                </option>
                            </select>
                            <div class="form-text">Change the status of this reservation.</div>
                        </div>
                        <div class="mb-3">
                            <label for="admin_notes{{ $reservation->id }}" class="form-label fw-semibold">Admin Notes <small class="text-muted">(optional)</small></label>
                            <textarea class="form-control" id="admin_notes{{ $reservation->id }}" name="admin_notes" rows="3" placeholder="Add any notes about this reservation status change...">{{ $reservation->admin_notes }}</textarea>
                            <div class="form-text">These notes will be saved with the reservation for future reference.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle me-1"></i>Update Reservation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal{{ $reservation->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $reservation->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel{{ $reservation->id }}">
                        <i class="bi bi-x me-2"></i>Reject Reservation #{{ $reservation->id }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.reservations.reject', $reservation->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Confirm Rejection</strong><br>
                            You are about to reject this reservation. Please provide a reason for rejection.
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary">Reservation Details</h6>
                            <p class="text-muted mb-3">
                                <strong>Property:</strong> {{ $reservation->property->name }}<br>
                                <strong>Contact:</strong> 
                                @if($reservation->isGuestReservation())
                                    {{ $reservation->guest_name }} ({{ $reservation->guest_email }})
                                @else
                                    {{ $reservation->user->name }} ({{ $reservation->user->email }})
                                @endif
                                <br>
                                <strong>Dates:</strong> {{ $reservation->start_time->format('M d, Y h:i A') }} - {{ $reservation->end_time->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        <div class="mb-3">
                            <label for="rejection_reason{{ $reservation->id }}" class="form-label fw-semibold">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejection_reason{{ $reservation->id }}" name="admin_notes" rows="4" required placeholder="Please provide a detailed reason for rejecting this reservation..."></textarea>
                            <div class="form-text">This reason will be saved as admin notes for future reference.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-arrow-left me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x me-1"></i>Reject Reservation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips for all elements with title attribute
    var titleElements = [].slice.call(document.querySelectorAll('button[title], [title]'));
    titleElements.forEach(function(element) {
        new bootstrap.Tooltip(element);
    });
    
    // Also initialize tooltips with data-bs-toggle="tooltip" if any
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection