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
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Search & Filter</h5>
            <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="false">
                <i class="bi bi-gear me-1"></i>Advanced Filters
            </button>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('home') }}" class="mb-0">
            <!-- Main Search Row -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Search Properties</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search by name, location, or description...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="type" class="form-label">Category</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">All Categories</option>
                        @foreach($propertyTypes as $propertyType)
                            <option value="{{ $propertyType }}" {{ request('type') === $propertyType ? 'selected' : '' }}>
                                {{ $propertyType }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i>Search
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </a>
                </div>
            </div>

            <!-- Advanced Filters (Collapsible) -->
            <div class="collapse" id="advancedFilters">
                <div class="row g-3 pt-3 border-top">
                    <div class="col-md-2">
                        <label for="capacity" class="form-label">Min Capacity</label>
                        <select class="form-select" id="capacity" name="capacity">
                            <option value="">Any Capacity</option>
                            <option value="1" {{ request('capacity') == '1' ? 'selected' : '' }}>1+ People</option>
                            <option value="5" {{ request('capacity') == '5' ? 'selected' : '' }}>5+ People</option>
                            <option value="10" {{ request('capacity') == '10' ? 'selected' : '' }}>10+ People</option>
                            <option value="25" {{ request('capacity') == '25' ? 'selected' : '' }}>25+ People</option>
                            <option value="50" {{ request('capacity') == '50' ? 'selected' : '' }}>50+ People</option>
                            <option value="100" {{ request('capacity') == '100' ? 'selected' : '' }}>100+ People</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="location" class="form-label">Location</label>
                        <select class="form-select" id="location" name="location">
                            <option value="">Any Location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location }}" {{ request('location') === $location ? 'selected' : '' }}>
                                    {{ $location }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="min_price" class="form-label">Min Price/Hour</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" 
                                   class="form-control" 
                                   id="min_price" 
                                   name="min_price" 
                                   value="{{ request('min_price') }}" 
                                   placeholder="0"
                                   min="0" 
                                   step="0.01">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="max_price" class="form-label">Max Price/Hour</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" 
                                   class="form-control" 
                                   id="max_price" 
                                   name="max_price" 
                                   value="{{ request('max_price') }}" 
                                   placeholder="{{ $priceRange->max_price ?? '1000' }}"
                                   min="0" 
                                   step="0.01">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="sort_by" class="form-label">Sort By</label>
                        <div class="input-group">
                            <select class="form-select" id="sort_by" name="sort_by">
                                <option value="name" {{ request('sort_by', 'name') === 'name' ? 'selected' : '' }}>Name</option>
                                <option value="price_per_hour" {{ request('sort_by') === 'price_per_hour' ? 'selected' : '' }}>Price</option>
                                <option value="capacity" {{ request('sort_by') === 'capacity' ? 'selected' : '' }}>Capacity</option>
                                <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Newest</option>
                            </select>
                            <select class="form-select" name="sort_direction" style="max-width: 100px;">
                                <option value="asc" {{ request('sort_direction', 'asc') === 'asc' ? 'selected' : '' }}>↑</option>
                                <option value="desc" {{ request('sort_direction') === 'desc' ? 'selected' : '' }}>↓</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Quick Category Filter Buttons -->
<div class="mb-4">
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('home') }}" 
           class="btn {{ !request('type') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
            <i class="bi bi-grid me-1"></i>All Categories
        </a>
        @foreach($propertyTypes as $propertyType)
            <a href="{{ route('home', array_merge(request()->query(), ['type' => $propertyType])) }}" 
               class="btn {{ request('type') === $propertyType ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                @if($propertyType === 'Park')
                    <i class="bi bi-tree me-1"></i>
                @elseif($propertyType === 'Conference Room')
                    <i class="bi bi-people me-1"></i>
                @else
                    <i class="bi bi-tools me-1"></i>
                @endif
                {{ $propertyType }}
            </a>
        @endforeach
    </div>
    
    <!-- Quick Preset Filters -->
    <div class="d-flex flex-wrap gap-2">
        <small class="text-muted me-2 align-self-center">Quick filters:</small>
        <a href="{{ route('home', ['min_price' => 0, 'max_price' => 0]) }}" 
           class="btn btn-outline-success btn-sm {{ request('min_price') == 0 && request('max_price') == 0 ? 'active' : '' }}">
            <i class="bi bi-gift me-1"></i>Free Only
        </a>
        <a href="{{ route('home', ['capacity' => 50]) }}" 
           class="btn btn-outline-info btn-sm {{ request('capacity') == 50 ? 'active' : '' }}">
            <i class="bi bi-people-fill me-1"></i>Large Groups (50+)
        </a>
        <a href="{{ route('home', ['sort_by' => 'price_per_hour', 'sort_direction' => 'asc']) }}" 
           class="btn btn-outline-warning btn-sm {{ request('sort_by') == 'price_per_hour' && request('sort_direction') == 'asc' ? 'active' : '' }}">
            <i class="bi bi-sort-numeric-up me-1"></i>Lowest Price
        </a>
        <a href="{{ route('home', ['sort_by' => 'created_at', 'sort_direction' => 'desc']) }}" 
           class="btn btn-outline-secondary btn-sm {{ request('sort_by') == 'created_at' && request('sort_direction') == 'desc' ? 'active' : '' }}">
            <i class="bi bi-clock me-1"></i>Newest
        </a>
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
                            @if($property->type === 'Park')
                                <i class="bi bi-tree" style="font-size: 3rem; color: #6b7280;"></i>
                            @elseif($property->type === 'Conference Room')
                                <i class="bi bi-people" style="font-size: 3rem; color: #6b7280;"></i>
                            @else
                                <i class="bi bi-tools" style="font-size: 3rem; color: #6b7280;"></i>
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
                        <span class="badge" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white;">
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

