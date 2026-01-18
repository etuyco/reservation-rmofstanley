@extends('layouts.app')

@section('title', 'Properties - RM of Stanley')

@section('styles')
<style>
/* Enhanced Property Filtering Styles */
.property-card {
    border: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.property-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.filter-section .card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #e2e8f0;
}

.category-filter-btn {
    transition: all 0.2s ease;
}

.category-filter-btn:hover {
    transform: scale(1.02);
}

.quick-filter-btn.active {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border-color: #1d4ed8;
    color: white;
}

.results-summary {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    border: 1px solid #93c5fd;
}

/* Loading animation */
.btn-loading {
    position: relative;
    color: transparent !important;
}

.btn-loading::after {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .property-card {
        margin-bottom: 1rem;
    }
    .filter-section .row > * {
        margin-bottom: 0.75rem;
    }
}
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1><i class="bi bi-building me-2"></i>Available Properties</h1>
            <p class="text-muted mb-0">Discover and reserve RM of Stanley properties</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-primary">{{ $totalProperties }} Total</span>
            <span class="badge bg-success">{{ $availableNow }} Available</span>
        </div>
    </div>
</div>

<!-- Advanced Search & Filter Section -->

<!-- Quick Category Filter Buttons -->
<div class="mb-4">
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('home') }}" 
           class="btn {{ !request('type') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
            <i class="bi bi-grid me-1"></i>All Properties
        </a>
        @foreach($categories ?? [] as $category)
            <a href="{{ route('home', array_merge(request()->query(), ['type' => $category->name])) }}" 
               class="btn {{ request('type') === $category->name ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                @if($category->icon)
                    <i class="{{ $category->icon }} me-1"></i>
                @endif
                {{ $category->name }}
            </a>
        @endforeach
    </div>
</div>

<!-- Results Summary -->
@if(request()->hasAny(['search', 'type', 'capacity', 'min_price', 'max_price']))
    <div class="alert alert-info mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-info-circle me-2"></i>
                <strong>{{ $properties->total() }} result{{ $properties->total() !== 1 ? 's' : '' }} found</strong>
                @if(request('search'))
                    for "<strong>{{ request('search') }}</strong>"
                @endif
                @if(request('type'))
                    in <strong>{{ request('type') }}</strong>
                @endif
            </div>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-x me-1"></i>Clear Filters
            </a>
        </div>
    </div>
@endif

<div class="row g-4">
    @forelse($properties as $property)
        <div class="col-lg-4 col-md-6">
            <div class="card property-card h-100">
                <!-- Property Image -->
                <div class="position-relative overflow-hidden" style="height: 200px;">
                    @if($property->image)
                        <img src="{{ $property->image }}" 
                             alt="{{ $property->name }}" 
                             class="w-100 h-100" 
                             style="object-fit: cover;">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center" 
                             style="background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);">
                            @if($property->category && $property->category->icon)
                                <i class="{{ $property->category->icon }}" style="font-size: 3rem; color: #6b7280;"></i>
                            @else
                                <i class="bi bi-building" style="font-size: 3rem; color: #6b7280;"></i>
                            @endif
                        </div>
                    @endif
                    
                    <!-- Status Badge Overlay -->
                    @php
                        $status = $property->current_status;
                    @endphp
                    <div class="position-absolute top-0 end-0 m-2">
                        @if($status === 'available')
                            <span class="status-badge status-available">
                                <i class="bi bi-check-circle me-1"></i>Available
                            </span>
                        @elseif($status === 'in_use')
                            <span class="status-badge status-in-use">
                                <i class="bi bi-x-circle me-1"></i>In Use
                            </span>
                        @elseif($status === 'reserved')
                            <span class="status-badge status-reserved">
                                <i class="bi bi-clock me-1"></i>Reserved
                            </span>
                        @endif
                    </div>

                    <!-- Property Type Badge -->
                    <div class="position-absolute bottom-0 start-0 m-2">
                        <span class="badge" style="background: {{ $property->category && $property->category->color ? $property->category->color : '#3b82f6' }}; color: white;">
                            @if($property->category && $property->category->icon)
                                <i class="{{ $property->category->icon }} me-1"></i>
                            @endif
                            {{ $property->type }}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <h4 class="card-title mb-2 fw-bold">{{ $property->name }}</h4>
                        @if($property->location)
                            <p class="text-muted mb-0">
                                <i class="bi bi-geo-alt-fill me-2"></i>{{ $property->location }}
                            </p>
                        @endif
                    </div>

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
                        <div class="col-6">
                            <div class="d-flex align-items-center text-muted">
                                <i class="bi bi-currency-dollar me-2" style="font-size: 1.25rem;"></i>
                                <div>
                                    <small class="d-block" style="font-size: 0.75rem; opacity: 0.7;">Price/Hour</small>
                                    <strong class="text-dark">
                                        @if($property->price_per_hour == 0 || $property->price_per_hour === null)
                                            <span class="text-success">FREE</span>
                                        @else
                                            ${{ number_format($property->price_per_hour, 2) }}
                                        @endif
                                    </strong>
                                </div>
                            </div>
                        </div>
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

<!-- Pagination -->
@if($properties->hasPages())
    <div class="d-flex justify-content-center mt-5">
        {{ $properties->links() }}
    </div>
@endif

<!-- Additional JavaScript for Enhanced UX -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on select change for better UX
    const quickFilters = document.querySelectorAll('#type, #sort_by, select[name="sort_direction"]');
    quickFilters.forEach(function(select) {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    // Add loading states
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    form.addEventListener('submit', function() {
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Searching...';
        submitBtn.disabled = true;
    });

    // Clear individual filters
    const clearButtons = document.querySelectorAll('.clear-filter');
    clearButtons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const input = this.parentNode.querySelector('input, select');
            input.value = '';
            form.submit();
        });
    });
});
</script>

@endsection

