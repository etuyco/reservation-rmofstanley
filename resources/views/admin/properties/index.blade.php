@extends('layouts.app')

@section('title', 'Manage Properties - RM of Stanley')

@section('styles')
<style>
.property-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    transition: all 0.3s ease;
    overflow: hidden;
}

.property-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
}

.property-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
}

.property-card .card-body {
    position: relative;
    padding: 1.5rem;
}

.stats-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.search-filter-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 1px solid #e5e7eb;
    border-radius: 12px;
}

.property-type-badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.action-btn {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.action-btn:hover {
    transform: scale(1.1);
}

.status-indicator {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background: rgba(34, 197, 94, 0.1);
    color: #16a34a;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.status-inactive {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
    border: 1px solid rgba(107, 114, 128, 0.2);
}

.view-toggle-btn {
    border-radius: 8px;
    padding: 0.5rem 1rem;
    border: 1px solid #d1d5db;
    background: white;
    transition: all 0.2s ease;
}

.view-toggle-btn.active {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.property-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f3f4f6;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6b7280;
}

.empty-state-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.3;
}

@media (max-width: 768px) {
    .property-card {
        margin-bottom: 1.5rem;
    }
    
    .stats-card {
        margin-bottom: 1rem;
    }
}

/* Modern Table Styles */
.modern-table-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.modern-table {
    font-size: 0.9rem;
    margin-bottom: 0;
}

.modern-table thead th {
    background: linear-gradient(135deg, #fafbfc 0%, #f4f6f8 100%);
    border-bottom: 2px solid #e9ecef;
    font-weight: 600;
}

.table-row-hover {
    transition: all 0.2s ease;
    cursor: default;
}

.table-row-hover:hover {
    background-color: #f8fafc !important;
    transform: scale(1.001);
}

.modern-type-badge {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    color: #475569;
    border: 1px solid #cbd5e1;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    padding: 0.4rem 0.8rem;
    text-transform: capitalize;
}

.modern-price-badge {
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.4rem 0.8rem;
    text-transform: uppercase;
}

.free-badge {
    background: linear-gradient(135deg, #d1fae5 0%, #bbf7d0 100%);
    color: #15803d;
    border: 1px solid #86efac;
}

.modern-status-badge {
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    padding: 0.4rem 0.8rem;
    text-transform: capitalize;
}

.active-badge {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #16a34a;
    border: 1px solid #86efac;
}

.inactive-badge {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    color: #6b7280;
    border: 1px solid #cbd5e1;
}

.modern-action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background: white;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    font-size: 0.85rem;
    padding: 0;
}

.modern-action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.view-btn {
    color: #3b82f6;
    border-color: #dbeafe;
}

.view-btn:hover {
    background: #dbeafe;
    color: #1d4ed8;
    border-color: #93c5fd;
}

.edit-btn {
    color: #f59e0b;
    border-color: #fef3c7;
}

.edit-btn:hover {
    background: #fef3c7;
    color: #d97706;
    border-color: #fcd34d;
}

.delete-btn {
    color: #ef4444;
    border-color: #fecaca;
}

.delete-btn:hover {
    background: #fecaca;
    color: #dc2626;
    border-color: #fca5a5;
}

/* Empty state styling for table */
.table-empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: #64748b;
}

.table-empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.3;
}
</style>
@endsection

@section('content')
<!-- Header Section -->
<div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap">
        <div>
            <h1><i class="bi bi-building me-2 text-primary"></i>Manage Properties</h1>
            <p class="text-muted mb-0">Create, edit, and manage all venue properties</p>
        </div>
        <div class="d-flex gap-2 mt-2 mt-md-0">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="bi bi-eye me-2"></i>View Public
            </a>
            <a href="{{ route('admin.properties.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i> Add Property
            </a>
        </div>
    </div>
</div>

<!-- Statistics Overview -->
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stats-card p-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-building fs-5"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="fw-bold fs-4">{{ $properties->count() }}</div>
                    <div class="text-muted small">Total Properties</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stats-card p-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-check-circle fs-5"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="fw-bold fs-4">{{ $properties->where('is_active', true)->count() }}</div>
                    <div class="text-muted small">Active Properties</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stats-card p-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-grid-3x3-gap fs-5"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="fw-bold fs-4">{{ $properties->groupBy('type')->count() }}</div>
                    <div class="text-muted small">Property Types</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stats-card p-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-gift fs-5"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="fw-bold fs-4">{{ $properties->where('price_per_hour', 0)->count() }}</div>
                    <div class="text-muted small">Free Properties</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Controls -->
<div class="card search-filter-card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search properties...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="type">
                    <option value="">All Types</option>
                    @foreach($properties->pluck('type')->unique()->filter() as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x me-1"></i>Clear
                    </a>
                    <button type="button" class="view-toggle-btn active" id="cardView">
                        <i class="bi bi-grid"></i>
                    </button>
                    <button type="button" class="view-toggle-btn" id="tableView">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Properties Grid/Cards View -->
<div id="cardsContainer">
    @if($properties->count() > 0)
        <div class="row g-4">
            @foreach($properties as $property)
                <div class="col-lg-4 col-md-6">
                    <div class="property-card card h-100">
                        <!-- Property Image -->
                        <div class="position-relative">
                            @if($property->image)
                                <img src="{{ $property->image }}" 
                                     alt="{{ $property->name }}" 
                                     class="property-image">
                            @else
                                <div class="property-image d-flex align-items-center justify-content-center">
                                    @if($property->type === 'Park')
                                        <i class="bi bi-tree text-muted" style="font-size: 3rem;"></i>
                                    @elseif($property->type === 'Conference Room')
                                        <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                    @else
                                        <i class="bi bi-tools text-muted" style="font-size: 3rem;"></i>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            <div class="status-indicator {{ $property->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $property->is_active ? 'Active' : 'Inactive' }}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $property->name }}</h5>
                                <span class="property-type-badge" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white;">
                                    {{ $property->type }}
                                </span>
                            </div>

                            <p class="text-muted small mb-3">{{ Str::limit($property->description, 80) }}</p>

                            <!-- Property Meta Information -->
                            <div class="property-meta">
                                @if($property->location)
                                    <div class="meta-item">
                                        <i class="bi bi-geo-alt"></i>
                                        <span>{{ $property->location }}</span>
                                    </div>
                                @endif
                                
                                @if($property->capacity)
                                    <div class="meta-item">
                                        <i class="bi bi-people"></i>
                                        <span>{{ $property->capacity }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Price Information -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="price-info">
                                    @if($property->price_per_hour == 0)
                                        <span class="badge bg-success fs-6">FREE</span>
                                    @elseif($property->price_per_hour)
                                        <span class="fw-bold text-primary fs-5">${{ number_format($property->price_per_hour, 2) }}</span>
                                        <small class="text-muted">/hour</small>
                                    @else
                                        <span class="text-muted">Price not set</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('properties.show', $property) }}" 
                                   class="action-btn btn btn-outline-primary" 
                                   title="View Property">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.properties.edit', $property) }}" 
                                   class="action-btn btn btn-outline-warning" 
                                   title="Edit Property">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.properties.destroy', $property) }}" 
                                      method="POST" 
                                      class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this property?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="action-btn btn btn-outline-danger" 
                                            title="Delete Property">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-building empty-state-icon"></i>
            <h4>No Properties Found</h4>
            <p class="mb-3">Start building your property portfolio by adding your first venue.</p>
            <a href="{{ route('admin.properties.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add Your First Property
            </a>
        </div>
    @endif
</div>

<!-- Table View (Hidden by default) -->
<div id="tableContainer" style="display: none;">
    <div class="card modern-table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; padding: 1.5rem 1rem 1rem;">Property</th>
                            <th class="border-0 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; padding: 1.5rem 1rem 1rem;">Type</th>
                            <th class="border-0 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; padding: 1.5rem 1rem 1rem;">Location</th>
                            <th class="border-0 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; padding: 1.5rem 1rem 1rem;">Capacity</th>
                            <th class="border-0 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; padding: 1.5rem 1rem 1rem;">Price/Hour</th>
                            <th class="border-0 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; padding: 1.5rem 1rem 1rem;">Status</th>
                            <th class="border-0 text-muted fw-semibold text-uppercase text-end" style="font-size: 0.75rem; letter-spacing: 0.5px; padding: 1.5rem 1rem 1rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($properties as $property)
                            <tr class="table-row-hover">
                                <td class="py-3 px-4 border-top" style="border-color: #f1f5f9 !important;">
                                    <div class="d-flex align-items-center">
                                        @if($property->image)
                                            <img src="{{ $property->image }}" 
                                                 alt="{{ $property->name }}" 
                                                 class="rounded-3 me-3 shadow-sm" 
                                                 style="width: 48px; height: 48px; object-fit: cover;">
                                        @else
                                            <div class="rounded-3 me-3 d-flex align-items-center justify-content-center shadow-sm" 
                                                 style="width: 48px; height: 48px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0;">
                                                @if($property->type === 'Park')
                                                    <i class="bi bi-tree text-success"></i>
                                                @elseif($property->type === 'Conference Room')
                                                    <i class="bi bi-people text-primary"></i>
                                                @else
                                                    <i class="bi bi-tools text-warning"></i>
                                                @endif
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold text-dark mb-1" style="font-size: 0.95rem;">{{ $property->name }}</div>
                                            <small class="text-muted">{{ Str::limit($property->description, 45) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 border-top" style="border-color: #f1f5f9 !important;">
                                    <span class="badge modern-type-badge">
                                        @if($property->type === 'Park')
                                            <i class="bi bi-tree me-1"></i>
                                        @elseif($property->type === 'Conference Room')
                                            <i class="bi bi-people me-1"></i>
                                        @else
                                            <i class="bi bi-tools me-1"></i>
                                        @endif
                                        {{ $property->type }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-top text-muted" style="border-color: #f1f5f9 !important;">
                                    @if($property->location)
                                        <i class="bi bi-geo-alt me-1 text-muted"></i>{{ $property->location }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 border-top" style="border-color: #f1f5f9 !important;">
                                    @if($property->capacity)
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="bi bi-people me-2"></i>
                                            <span class="fw-medium">{{ $property->capacity }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 border-top" style="border-color: #f1f5f9 !important;">
                                    @if($property->price_per_hour == 0)
                                        <span class="badge modern-price-badge free-badge">
                                            <i class="bi bi-gift me-1"></i>FREE
                                        </span>
                                    @elseif($property->price_per_hour)
                                        <div class="fw-semibold text-dark">${{ number_format($property->price_per_hour, 2) }}</div>
                                        <small class="text-muted">per hour</small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 border-top" style="border-color: #f1f5f9 !important;">
                                    @if($property->is_active)
                                        <span class="badge modern-status-badge active-badge">
                                            <i class="bi bi-check-circle me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge modern-status-badge inactive-badge">
                                            <i class="bi bi-pause-circle me-1"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 border-top text-end" style="border-color: #f1f5f9 !important;">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('properties.show', $property) }}" 
                                           class="btn modern-action-btn view-btn" 
                                           title="View Property"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.properties.edit', $property) }}" 
                                           class="btn modern-action-btn edit-btn" 
                                           title="Edit Property"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.properties.destroy', $property) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this property?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn modern-action-btn delete-btn" 
                                                    title="Delete Property"
                                                    data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cardViewBtn = document.getElementById('cardView');
    const tableViewBtn = document.getElementById('tableView');
    const cardsContainer = document.getElementById('cardsContainer');
    const tableContainer = document.getElementById('tableContainer');

    // View toggle functionality
    cardViewBtn.addEventListener('click', function() {
        cardViewBtn.classList.add('active');
        tableViewBtn.classList.remove('active');
        cardsContainer.style.display = 'block';
        tableContainer.style.display = 'none';
        localStorage.setItem('propertiesView', 'cards');
    });

    tableViewBtn.addEventListener('click', function() {
        tableViewBtn.classList.add('active');
        cardViewBtn.classList.remove('active');
        cardsContainer.style.display = 'none';
        tableContainer.style.display = 'block';
        localStorage.setItem('propertiesView', 'table');
    });

    // Restore saved view preference
    const savedView = localStorage.getItem('propertiesView');
    if (savedView === 'table') {
        tableViewBtn.click();
    }

    // Auto-submit on filter change
    const filterSelects = document.querySelectorAll('select[name="type"], select[name="status"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
});
</script>
@endsection

