@extends('layouts.app')

@section('title', 'All Bookings - Admin - RM of Stanley')

@section('content')
<div class="row mb-4">
    <div class="col">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
        <h1>All Bookings</h1>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Property</th>
                <th>User</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->property->name }}</td>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->start_time->format('M d, Y h:i A') }}</td>
                    <td>{{ $booking->end_time->format('M d, Y h:i A') }}</td>
                    <td>
                        @if($booking->status === 'pending')
                            <span class="status-badge status-pending">Pending</span>
                        @elseif($booking->status === 'approved')
                            <span class="status-badge status-approved">Approved</span>
                        @elseif($booking->status === 'rejected')
                            <span class="status-badge status-rejected">Rejected</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($booking->status === 'pending')
                            <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                            </form>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $booking->id }}">
                                Reject
                            </button>
                            
                            <div class="modal fade" id="rejectModal{{ $booking->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Booking</h5>
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
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No bookings found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

