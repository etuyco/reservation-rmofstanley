@extends('layouts.app')

@section('title', 'Admin Dashboard - RM of Stanley')

@section('styles')
<style>
.stat-card {
    border: none;
    border-radius: 16px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    transform: translate(30px, -30px);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
}

.activity-item {
    transition: all 0.2s ease;
    border-radius: 8px;
    padding: 12px;
}

.activity-item:hover {
    background: #f8f9fa;
    transform: translateX(5px);
}

.quick-action-card {
    border: none;
    border-radius: 12px;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
}

.quick-action-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 8px;
}

.status-pending { background: #fbbf24; }
.status-approved { background: #10b981; }
.status-rejected { background: #ef4444; }

.chart-container {
    position: relative;
    height: 300px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e2e8f0;
}

.progress-ring {
    width: 120px;
    height: 120px;
}

.progress-ring circle {
    fill: transparent;
    stroke-width: 8;
    stroke-linecap: round;
    transform: rotate(-90deg);
    transform-origin: center;
}

.metric-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.metric-card:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

@media (max-width: 768px) {
    .stat-card {
        margin-bottom: 1rem;
    }
    
    .chart-container {
        height: 250px;
        padding: 15px;
    }
}
</style>
@endsection

@section('content')
<!-- Header Section -->
<div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1><i class="bi bi-speedometer2 me-2 text-primary"></i>Admin Dashboard</h1>
            <p class="text-muted mb-0">Welcome back! Here's what's happening with your properties today.</p>
        </div>
        <div class="text-end">
            <small class="text-muted d-block">Last updated</small>
            <strong>{{ now()->format('M d, Y \a\t g:i A') }}</strong>
        </div>
    </div>
</div>

<!-- Key Metrics Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card text-white h-100" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-white-50 mb-2" style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="bi bi-clock-history me-1"></i>Pending Bookings
                        </h6>
                        <h2 class="mb-1 fw-bold">{{ $pendingBookings->count() }}</h2>
                        <small class="text-white-75">Require approval</small>
                    </div>
                    <i class="bi bi-calendar-check" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card text-white h-100" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-white-50 mb-2" style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="bi bi-hourglass-split me-1"></i>Pending Reservations
                        </h6>
                        <h2 class="mb-1 fw-bold">{{ $pendingReservations->count() }}</h2>
                        <small class="text-white-75">Awaiting review</small>
                    </div>
                    <i class="bi bi-calendar-event" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card text-white h-100" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-white-50 mb-2" style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="bi bi-building me-1"></i>Total Properties
                        </h6>
                        <h2 class="mb-1 fw-bold">{{ $properties->count() }}</h2>
                        <small class="text-white-75">Available venues</small>
                    </div>
                    <i class="bi bi-building" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card text-white h-100" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-white-50 mb-2" style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="bi bi-graph-up me-1"></i>Active Today
                        </h6>
                        <h2 class="mb-1 fw-bold">{{ $properties->where('is_active', true)->count() }}</h2>
                        <small class="text-white-75">Properties online</small>
                    </div>
                    <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row g-4">
    <!-- Recent Activity & Quick Actions -->
    <div class="col-lg-8">
        <!-- Recent Activity -->
        <div class="card mb-4">
            <div class="card-header border-0 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-activity text-primary me-2"></i>Recent Activity
                    </h5>
                    <small class="text-muted">Last 24 hours</small>
                </div>
            </div>
            <div class="card-body">
                @if($pendingBookings->count() > 0 || $pendingReservations->count() > 0)
                    <div class="activity-list">
                        @foreach($pendingBookings->take(3) as $booking)
                            <div class="activity-item d-flex align-items-center mb-3">
                                <div class="status-indicator status-pending flex-shrink-0"></div>
                                <i class="bi bi-calendar-check text-warning me-3 fs-5"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>New booking request</strong> for {{ $booking->property->name }}
                                            <div class="text-muted small">
                                                <i class="bi bi-person me-1"></i>{{ $booking->user->name }}
                                                <span class="mx-2">•</span>
                                                <i class="bi bi-clock me-1"></i>{{ $booking->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.bookings') }}" class="btn btn-outline-warning btn-sm">
                                            <i class="bi bi-eye me-1"></i>Review
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @foreach($pendingReservations->take(3) as $reservation)
                            <div class="activity-item d-flex align-items-center mb-3">
                                <div class="status-indicator status-pending flex-shrink-0"></div>
                                <i class="bi bi-calendar-event text-info me-3 fs-5"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>New reservation</strong> for {{ $reservation->property->name }}
                                            <div class="text-muted small">
                                                <i class="bi bi-person me-1"></i>{{ $reservation->guest_name }}
                                                <span class="mx-2">•</span>
                                                <i class="bi bi-clock me-1"></i>{{ $reservation->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.reservations') }}" class="btn btn-outline-info btn-sm">
                                            <i class="bi bi-eye me-1"></i>Review
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">All caught up!</h5>
                        <p class="text-muted mb-0">No pending items to review at the moment.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Property Types Overview -->
        <div class="card">
            <div class="card-header border-0">
                <h5 class="mb-0">
                    <i class="bi bi-pie-chart text-primary me-2"></i>Property Distribution
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $propertyTypes = $properties->groupBy('type');
                        $colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
                        $colorIndex = 0;
                    @endphp
                    @foreach($propertyTypes as $type => $typeProperties)
                        <div class="col-md-4 mb-3">
                            <div class="metric-card text-center">
                                <div class="d-flex justify-content-center mb-2">
                                    <div class="progress-ring">
                                        <svg width="60" height="60">
                                            <circle cx="30" cy="30" r="25" 
                                                    stroke="#e5e7eb" stroke-width="4" fill="transparent"/>
                                            <circle cx="30" cy="30" r="25" 
                                                    stroke="{{ $colors[$colorIndex % count($colors)] }}" 
                                                    stroke-width="4" 
                                                    fill="transparent"
                                                    stroke-dasharray="{{ (($typeProperties->count() / $properties->count()) * 157) }} 157"/>
                                        </svg>
                                        <div class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                            <strong>{{ $typeProperties->count() }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="mb-1">{{ $type }}</h6>
                                <small class="text-muted">
                                    {{ number_format(($typeProperties->count() / $properties->count()) * 100, 1) }}% of total
                                </small>
                            </div>
                        </div>
                        @php $colorIndex++; @endphp
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Summary -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header border-0">
                <h5 class="mb-0">
                    <i class="bi bi-lightning text-primary me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="d-grid gap-2 p-3">
                    <a href="{{ route('admin.properties.create') }}" class="btn btn-primary d-flex align-items-center">
                        <i class="bi bi-plus-circle me-2"></i>
                        <div class="text-start">
                            <div class="fw-semibold">Add New Property</div>
                            <small class="opacity-75">Create a new venue</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-info d-flex align-items-center">
                        <i class="bi bi-building me-2"></i>
                        <div class="text-start">
                            <div class="fw-semibold">Manage Properties</div>
                            <small class="opacity-75">Edit existing venues</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.reservations') }}" class="btn btn-outline-warning d-flex align-items-center">
                        <i class="bi bi-calendar-event me-2"></i>
                        <div class="text-start">
                            <div class="fw-semibold">View Reservations</div>
                            <small class="opacity-75">Manage bookings</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="card">
            <div class="card-header border-0">
                <h5 class="mb-0">
                    <i class="bi bi-shield-check text-success me-2"></i>System Status
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <div class="status-indicator status-approved"></div>
                        <span>Database</span>
                    </div>
                    <span class="badge bg-success">Online</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <div class="status-indicator status-approved"></div>
                        <span>Image Storage</span>
                    </div>
                    <span class="badge bg-success">Active</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <div class="status-indicator status-approved"></div>
                        <span>Email Service</span>
                    </div>
                    <span class="badge bg-success">Running</span>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        Last checked: {{ now()->format('g:i A') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

