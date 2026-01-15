@extends('layouts.app')

@section('title', 'Properties - RM of Stanley')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1><i class="bi bi-building me-2"></i>Available Properties</h1>
            <p class="text-muted mb-0">Discover and reserve RM of Stanley properties</p>
        </div>
    </div>
</div>

<div class="row g-4">
    @forelse($properties as $property)
        <div class="col-lg-4 col-md-6">
            <div class="card property-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="card-title mb-2 fw-bold">{{ $property->name }}</h4>
                            <span class="badge" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white;">
                                {{ $property->type }}
                            </span>
                        </div>
                        @php
                            $status = $property->current_status;
                        @endphp
                        @if($status === 'available')
                            <span class="status-badge status-available">
                                <i class="bi bi-check-circle"></i>
                            </span>
                        @elseif($status === 'in_use')
                            <span class="status-badge status-in-use">
                                <i class="bi bi-x-circle"></i>
                            </span>
                        @elseif($status === 'reserved')
                            <span class="status-badge status-reserved">
                                <i class="bi bi-clock"></i>
                            </span>
                        @endif
                    </div>

                    @if($property->location)
                        <p class="text-muted mb-3">
                            <i class="bi bi-geo-alt-fill me-2"></i>{{ $property->location }}
                        </p>
                    @endif

                    @if($property->description)
                        <p class="card-text text-muted mb-4" style="line-height: 1.6;">
                            {{ Str::limit($property->description, 120) }}
                        </p>
                    @endif

                    <div class="row g-3 mb-3">
                        @if($property->capacity)
                            <div class="col-6">
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-people-fill me-2" style="font-size: 1.25rem;"></i>
                                    <div>
                                        <small class="d-block" style="font-size: 0.75rem; opacity: 0.7;">Capacity</small>
                                        <strong class="text-dark">{{ $property->capacity }}</strong>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($property->price_per_hour)
                            <div class="col-6">
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-currency-dollar me-2" style="font-size: 1.25rem;"></i>
                                    <div>
                                        <small class="d-block" style="font-size: 0.75rem; opacity: 0.7;">Price/Hour</small>
                                        <strong class="text-dark">${{ number_format($property->price_per_hour, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('properties.show', $property) }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                        <span>View Details</span>
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #cbd5e0;"></i>
                    <h4 class="mt-3 mb-2">No Properties Available</h4>
                    <p class="text-muted mb-0">Check back later for available properties.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection

