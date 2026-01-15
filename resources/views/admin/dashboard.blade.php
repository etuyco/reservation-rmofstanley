@extends('layouts.app')

@section('title', 'Admin Dashboard - RM of Stanley')

@section('content')
<div class="page-header mb-4">
    <h1><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h1>
    <p class="text-muted mb-0">Manage bookings, reservations, and properties</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-white-50 mb-2" style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">Pending Bookings</h6>
                        <h2 class="mb-0 fw-bold">{{ $pendingBookings->count() }}</h2>
                    </div>
                    <i class="bi bi-calendar-check" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-white-50 mb-2" style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">Pending Reservations</h6>
                        <h2 class="mb-0 fw-bold">{{ $pendingReservations->count() }}</h2>
                    </div>
                    <i class="bi bi-calendar-event" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-white-50 mb-2" style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">Total Properties</h6>
                        <h2 class="mb-0 fw-bold">{{ $properties->count() }}</h2>
                    </div>
                    <i class="bi bi-building" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row mt-4">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-link-45deg me-2"></i>Quick Links</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-info">
                        <i class="bi bi-building me-2"></i> Manage Properties
                    </a>
                    <!-- <a href="{{ route('admin.bookings') }}" class="btn btn-outline-primary">
                        <i class="bi bi-calendar-check me-2"></i> All Bookings
                    </a> -->
                    <a href="{{ route('admin.reservations') }}" class="btn btn-outline-warning">
                        <i class="bi bi-calendar-event me-2"></i> All Reservations
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

