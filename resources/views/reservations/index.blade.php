@extends('layouts.app')

@section('title', 'My Reservations - RM of Stanley')

@section('styles')
<style>
.reservation-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
    background: white;
}

.reservation-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
}

.reservation-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--status-color, #6b7280);
    z-index: 1;
}

.reservation-card.status-pending::before { background: #f59e0b; }
.reservation-card.status-approved::before { background: #059669; }
.reservation-card.status-rejected::before { background: #dc2626; }
.reservation-card.status-completed::before { background: #6b7280; }
.reservation-card.status-cancelled::before { background: #374151; }

.filter-tabs {
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 2rem;
    background: white;
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.filter-tab {
    padding: 1rem 1.5rem;
    border: none;
    background: none;
    color: #6b7280;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    border-radius: 8px;
    margin: 0 0.25rem;
}

.filter-tab:hover {
    color: #374151;
    background: rgba(107, 114, 128, 0.1);
}

.filter-tab.active {
    color: #374151;
    font-weight: 600;
    background: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.time-section {
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
    transition: all 0.2s ease;
    background: white;
}

.time-section:hover {
    border-color: #6b7280;
    box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.1);
}

.time-header {
    padding: 1rem;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: #f8f9fa;
}

.time-content {
    padding: 1rem;
    background: white;
}

.status-badge {
    border-radius: 20px;
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background: rgba(245, 158, 11, 0.15);
    color: #92400e;
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.status-approved {
    background: rgba(5, 150, 105, 0.15);
    color: #047857;
    border: 1px solid rgba(5, 150, 105, 0.3);
}

.status-rejected {
    background: rgba(220, 38, 38, 0.15);
    color: #991b1b;
    border: 1px solid rgba(220, 38, 38, 0.3);
}

.status-completed {
    background: rgba(107, 114, 128, 0.15);
    color: #374151;
    border: 1px solid rgba(107, 114, 128, 0.3);
}

.quick-action-btn {
    border-radius: 12px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.quick-action-btn:hover {
    transform: translateY(-1px);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: #f8f9fa;
    border-radius: 16px;
    border: 2px dashed #d1d5db;
}

.empty-state-icon {
    font-size: 5rem;
    margin-bottom: 2rem;
    opacity: 0.3;
    color: #9ca3af;
}

.empty-filter-state {
    text-align: center;
    padding: 3rem 2rem;
    background: #f8f9fa;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    margin: 2rem 0;
    display: none;
}

.empty-filter-state.show {
    display: block;
}

.empty-filter-state i {
    font-size: 3rem;
    color: #9ca3af;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .reservation-card {
        margin-bottom: 1.5rem;
    }
    
    .filter-tab {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }
}
</style>
@endsection

@section('content')
<!-- Header Section -->
<div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1><i class="bi bi-calendar-event me-2 text-primary"></i>My Reservations</h1>
            <p class="text-muted mb-0">Track and manage your property reservations</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-primary mt-2 mt-md-0">
            <i class="bi bi-plus-circle me-2"></i>Make New Reservation
        </a>
    </div>
</div>

@php
    $totalReservations = $reservations->count();
    $pendingReservations = $reservations->where('status', 'pending')->count();
    $approvedReservations = $reservations->where('status', 'approved')->count();
    $upcomingReservations = $reservations->filter(function($r) { return $r->start_time->isFuture(); })->count();
    $completedReservations = $reservations->where('status', 'completed')->count();
@endphp

@if($reservations->isNotEmpty())
    <!-- Filter Tabs -->
    <div class="filter-tabs mb-4">
        <div class="d-flex flex-wrap">
            <button class="filter-tab active" data-filter="all">
                All Reservations <span class="badge bg-secondary ms-2">{{ $totalReservations }}</span>
            </button>
            <button class="filter-tab" data-filter="pending">
                Pending <span class="badge bg-warning ms-2">{{ $pendingReservations }}</span>
            </button>
            <button class="filter-tab" data-filter="approved">
                Approved <span class="badge bg-success ms-2">{{ $approvedReservations }}</span>
            </button>
            <button class="filter-tab" data-filter="upcoming">
                Upcoming <span class="badge bg-info ms-2">{{ $upcomingReservations }}</span>
            </button>
        </div>
    </div>

    <!-- Reservations List -->
    <div id="reservationsContainer">
        <!-- Empty State Messages for Filters -->
        <div id="emptyPending" class="empty-filter-state">
            <i class="bi bi-clock-history"></i>
            <h4>No Pending Reservations</h4>
            <p class="text-muted">You don't have any pending reservations at the moment.</p>
        </div>
        
        <div id="emptyApproved" class="empty-filter-state">
            <i class="bi bi-check-circle"></i>
            <h4>No Approved Reservations</h4>
            <p class="text-muted">You don't have any approved reservations currently.</p>
        </div>
        
        <div id="emptyUpcoming" class="empty-filter-state">
            <i class="bi bi-calendar-plus"></i>
            <h4>No Upcoming Reservations</h4>
            <p class="text-muted">You don't have any upcoming reservations scheduled.</p>
        </div>
        
        @foreach($reservations as $reservation)
            @php
                $duration = $reservation->start_time->diffInHours($reservation->end_time);
                $isPast = $reservation->end_time->isPast();
                $isToday = $reservation->start_time->isToday();
                $isUpcoming = $reservation->start_time->isFuture();
                $statusClass = 'status-' . $reservation->status;
            @endphp
            
            <div class="reservation-card card mb-4 {{ $statusClass }}" 
                 data-status="{{ $reservation->status }}" 
                 data-upcoming="{{ $isUpcoming ? 'true' : 'false' }}">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Property Information -->
                        <div class="col-lg-8">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div>
                                    <h5 class="card-title mb-2 fw-bold text-dark">{{ $reservation->property->name }}</h5>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge property-type-badge" style="background: #3b82f6; color: white; padding: 0.5rem 1rem; border-radius: 20px;">
                                            {{ $reservation->property->type }}
                                        </span>
                                        @if($reservation->property->location)
                                            <span class="text-muted">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $reservation->property->location }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <span class="status-badge status-{{ $reservation->status }}">
                                    @if($reservation->status === 'pending')
                                        <i class="bi bi-clock me-1"></i>Pending
                                    @elseif($reservation->status === 'approved')
                                        <i class="bi bi-check-circle me-1"></i>Approved
                                    @elseif($reservation->status === 'rejected')
                                        <i class="bi bi-x-circle me-1"></i>Rejected
                                    @elseif($reservation->status === 'completed')
                                        <i class="bi bi-check-square me-1"></i>Completed
                                    @elseif($reservation->status === 'cancelled')
                                        <i class="bi bi-dash-circle me-1"></i>Cancelled
                                    @endif
                                </span>
                            </div>

                            <!-- Time Information -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="time-section">
                                        <div class="time-header text-dark">
                                            <i class="bi bi-play-circle me-2"></i>Start Time
                                        </div>
                                        <div class="time-content">
                                            <div class="fw-bold text-dark">{{ $reservation->start_time->format('M d, Y') }}</div>
                                            <div class="text-muted">
                                                <i class="bi bi-clock me-1"></i>{{ $reservation->start_time->format('h:i A') }}
                                                @if($isToday)
                                                    <span class="badge bg-info ms-2" style="font-size: 0.7rem;">Today</span>
                                                @elseif($isUpcoming)
                                                    <span class="badge bg-success ms-2" style="font-size: 0.7rem;">Upcoming</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="time-section">
                                        <div class="time-header text-dark">
                                            <i class="bi bi-stop-circle me-2"></i>End Time
                                        </div>
                                        <div class="time-content">
                                            <div class="fw-bold text-dark">{{ $reservation->end_time->format('M d, Y') }}</div>
                                            <div class="text-muted">
                                                <i class="bi bi-clock me-1"></i>{{ $reservation->end_time->format('h:i A') }}
                                                @if($isPast)
                                                    <span class="badge bg-secondary ms-2" style="font-size: 0.7rem;">Past</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-hourglass-split"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Duration</div>
                                            <div class="fw-bold">{{ $duration }} {{ $duration == 1 ? 'hour' : 'hours' }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($reservation->guest_count)
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success bg-opacity-10 text-success rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-people"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted small">Guests</div>
                                                <div class="fw-bold">{{ $reservation->guest_count }} people</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($reservation->purpose)
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 text-info rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-info-circle"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted small">Purpose</div>
                                                <div class="fw-bold" title="{{ $reservation->purpose }}">
                                                    {{ Str::limit($reservation->purpose, 30) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-lg-4">
                            <div class="d-grid gap-3">
                                <a href="{{ route('reservations.show', $reservation) }}" 
                                   class="btn btn-primary quick-action-btn">
                                    <i class="bi bi-eye me-2"></i>View Details
                                </a>
                                
                                @if($reservation->property->image)
                                    <button type="button" class="btn btn-outline-info quick-action-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#propertyModal{{ $reservation->id }}">
                                        <i class="bi bi-image me-2"></i>View Property
                                    </button>
                                @endif

                                @if($reservation->status === 'pending')
                                    <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-outline-danger quick-action-btn w-100" 
                                                onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                            <i class="bi bi-x-circle me-2"></i>Cancel Reservation
                                        </button>
                                    </form>
                                @endif

                                @if($reservation->status === 'approved' && $isUpcoming)
                                    <div class="alert alert-success mb-0 p-3">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        <strong>Ready to go!</strong><br>
                                        <small>Your reservation is confirmed</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Modal -->
            @if($reservation->property->image)
                <div class="modal fade" id="propertyModal{{ $reservation->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $reservation->property->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-0">
                                <img src="{{ $reservation->property->image }}" 
                                     alt="{{ $reservation->property->name }}" 
                                     class="w-100" 
                                     style="max-height: 400px; object-fit: cover;">
                                <div class="p-3">
                                    <p class="text-muted mb-0">{{ $reservation->property->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@else
    <!-- Empty State -->
    <div class="empty-state">
        <i class="bi bi-calendar-x empty-state-icon"></i>
        <h3 class="mb-3">No Reservations Yet</h3>
        <p class="text-muted mb-4 lead">Start exploring our amazing properties and make your first reservation today!</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-building me-2"></i>Browse Properties
            </a>
            <a href="{{ route('home') }}?type=Conference%20Room" class="btn btn-outline-primary btn-lg">
                <i class="bi bi-people me-2"></i>Conference Rooms
            </a>
        </div>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterTabs = document.querySelectorAll('.filter-tab');
    const reservationCards = document.querySelectorAll('.reservation-card');
    const emptyStates = {
        'pending': document.getElementById('emptyPending'),
        'approved': document.getElementById('emptyApproved'),
        'upcoming': document.getElementById('emptyUpcoming')
    };

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');
            
            // Hide all empty states first
            Object.values(emptyStates).forEach(state => {
                if (state) state.classList.remove('show');
            });
            
            let visibleCount = 0;
            
            // Filter cards
            reservationCards.forEach(card => {
                const status = card.getAttribute('data-status');
                const isUpcoming = card.getAttribute('data-upcoming') === 'true';
                
                let show = false;
                
                switch(filter) {
                    case 'all':
                        show = true;
                        break;
                    case 'pending':
                        show = status === 'pending';
                        break;
                    case 'approved':
                        show = status === 'approved';
                        break;
                    case 'upcoming':
                        show = isUpcoming;
                        break;
                }
                
                card.style.display = show ? 'block' : 'none';
                if (show) visibleCount++;
            });
            
            // Show empty state if no cards are visible for this filter
            if (visibleCount === 0 && filter !== 'all' && emptyStates[filter]) {
                emptyStates[filter].classList.add('show');
            }
        });
    });

    // Add animation when cards appear
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    reservationCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(card);
    });
});
</script>
@endsection

