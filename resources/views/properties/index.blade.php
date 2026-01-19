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

/* Custom Pagination Styles */
.pagination-custom .page-link {
    border: none;
    padding: 0.75rem 1rem;
    margin: 0 0.125rem;
    border-radius: 0.5rem;
    color: #64748b;
    background-color: #f8fafc;
    transition: all 0.3s ease;
    font-weight: 500;
    text-decoration: none;
}

.pagination-custom .page-link:hover {
    background-color: #e2e8f0;
    color: #1e293b;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.pagination-custom .page-item.active .page-link {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    border-color: transparent;
}

.pagination-custom .page-item.disabled .page-link {
    background-color: #f1f5f9;
    color: #94a3b8;
    cursor: not-allowed;
    opacity: 0.6;
}

.pagination-custom .page-item.disabled .page-link:hover {
    background-color: #f1f5f9;
    transform: none;
    box-shadow: none;
}

.pagination-wrapper {
    background: white;
    padding: 1rem;
    border-radius: 0.75rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
}

/* Results info card styling */
.pagination-info {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border: 1px solid #cbd5e1;
}

@media (max-width: 768px) {
    .property-card {
        margin-bottom: 1rem;
    }
    .filter-section .row > * {
        margin-bottom: 0.75rem;
    }
    
    .pagination-custom .page-link {
        padding: 0.5rem 0.75rem;
        margin: 0 0.0625rem;
    }
    
    .pagination-custom .page-link .d-none {
        display: none !important;
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
        <!-- <a href="{{ route('home') }}" 
           class="btn {{ !request('type') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
            <i class="bi bi-grid me-1"></i>All Properties
        </a> -->
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

<!-- Enhanced Pagination Section -->
@if($properties->hasPages())
    <div class="row mt-5">
        <div class="col-12">
            <!-- Pagination Info Card -->
            <div class="card border-0 mb-4" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle text-primary me-2" style="font-size: 1.2rem;"></i>
                                <span class="text-muted">
                                    Showing <strong class="text-primary">{{ $properties->firstItem() }}</strong> to 
                                    <strong class="text-primary">{{ $properties->lastItem() }}</strong> of 
                                    <strong class="text-primary">{{ $properties->total() }}</strong> properties
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-2 mt-md-0">
                            <span class="badge bg-primary px-3 py-2" style="font-size: 0.9rem;">
                                Page {{ $properties->currentPage() }} of {{ $properties->lastPage() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pagination Navigation -->
            <div class="d-flex justify-content-center">
                <nav aria-label="Properties pagination">
                    <div class="pagination-wrapper">
                        {{ $properties->links('custom.pagination') }}
                    </div>
                </nav>
            </div>
            
            <!-- Quick Jump to Page (for large datasets) -->
            @if($properties->lastPage() > 10)
                <div class="text-center mt-3">
                    <div class="d-inline-flex align-items-center gap-2 p-2 rounded" style="background: rgba(59, 130, 246, 0.1);">
                        <small class="text-muted">Quick jump:</small>
                        <input type="number" 
                               id="pageJump" 
                               class="form-control form-control-sm" 
                               style="width: 80px;" 
                               min="1" 
                               max="{{ $properties->lastPage() }}" 
                               placeholder="{{ $properties->currentPage() }}"
                               title="Jump to page">
                        <button type="button" 
                                class="btn btn-sm btn-primary" 
                                onclick="jumpToPage()">
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            @endif
            
            <!-- Results per page selector -->
            <div class="text-center mt-3">
                <div class="d-inline-flex align-items-center gap-2">
                    <small class="text-muted">Show:</small>
                    <select class="form-select form-select-sm" style="width: auto;" onchange="changePerPage(this.value)">
                        <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12 per page</option>
                        <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24 per page</option>
                        <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48 per page</option>
                        <option value="96" {{ request('per_page') == 96 ? 'selected' : '' }}>96 per page</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- No pagination needed but show total count -->
    @if($properties->count() > 0)
        <div class="text-center mt-4">
            <div class="d-inline-flex align-items-center gap-2 p-3 rounded" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #bbf7d0;">
                <i class="bi bi-check-circle text-success"></i>
                <span class="text-success fw-semibold">
                    Showing all {{ $properties->count() }} {{ $properties->count() === 1 ? 'property' : 'properties' }}
                </span>
            </div>
        </div>
    @endif
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
    
    // Add smooth scroll to pagination clicks
    const paginationLinks = document.querySelectorAll('.pagination-custom .page-link');
    paginationLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (!this.closest('.page-item').classList.contains('disabled')) {
                // Add loading state
                this.innerHTML = '<i class="bi bi-hourglass-split"></i>';
                this.style.pointerEvents = 'none';
                
                // Scroll to top smoothly
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });
});

// Function to jump to a specific page
function jumpToPage() {
    const pageInput = document.getElementById('pageJump');
    const pageNumber = parseInt(pageInput.value);
    const maxPage = parseInt(pageInput.getAttribute('max'));
    const minPage = parseInt(pageInput.getAttribute('min'));
    
    if (pageNumber && pageNumber >= minPage && pageNumber <= maxPage) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('page', pageNumber);
        
        // Add loading state
        pageInput.disabled = true;
        const button = pageInput.nextElementSibling;
        button.innerHTML = '<i class="bi bi-hourglass-split"></i>';
        button.disabled = true;
        
        // Navigate to page
        window.location.href = currentUrl.toString();
    } else {
        // Show error feedback
        pageInput.classList.add('is-invalid');
        setTimeout(() => {
            pageInput.classList.remove('is-invalid');
        }, 2000);
    }
}

// Function to change results per page
function changePerPage(perPage) {
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('per_page', perPage);
    currentUrl.searchParams.delete('page'); // Reset to first page
    
    // Add loading state
    const select = event.target;
    select.disabled = true;
    
    // Navigate with new per_page value
    window.location.href = currentUrl.toString();
}

// Allow Enter key on page jump input
document.addEventListener('DOMContentLoaded', function() {
    const pageJumpInput = document.getElementById('pageJump');
    if (pageJumpInput) {
        pageJumpInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                jumpToPage();
            }
        });
    }
});
</script>

@endsection

