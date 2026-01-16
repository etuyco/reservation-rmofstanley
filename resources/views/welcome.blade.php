<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Stanley RM - Property Reservations</title>
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <!-- FontAwesome CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <style>
            :root {
                --primary-gradient: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
                --secondary-gradient: linear-gradient(135deg, #059669 0%, #10b981 100%);
                --success-gradient: linear-gradient(135deg, #059669 0%, #10b981 100%);
                --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
                --info-gradient: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
                --dark-gradient: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                --shadow-sm: 0 2px 4px rgba(0,0,0,0.08);
                --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
                --shadow-lg: 0 10px 25px rgba(0,0,0,0.15);
                --shadow-xl: 0 20px 40px rgba(0,0,0,0.2);
            }

            * {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            }

            body {
                background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
                min-height: 100vh;
                color: #2d3748;
            }

            /* Modern Navbar */
            .navbar {
                background: var(--primary-gradient) !important;
                box-shadow: var(--shadow-md);
                padding: 1rem 0;
                backdrop-filter: blur(10px);
            }

            .navbar-brand {
                font-weight: 700;
                font-size: 1.5rem;
                letter-spacing: -0.5px;
                transition: transform 0.3s ease;
            }

            .navbar-brand:hover {
                transform: scale(1.05);
            }

            .navbar-nav .nav-link {
                font-weight: 500;
                padding: 0.5rem 1rem !important;
                border-radius: 8px;
                transition: all 0.3s ease;
                margin: 0 0.25rem;
            }

            .navbar-nav .nav-link:hover {
                background: rgba(255, 255, 255, 0.15);
                transform: translateY(-2px);
            }

            .dropdown-menu {
                border: none;
                box-shadow: var(--shadow-lg);
                border-radius: 12px;
                padding: 0.5rem;
                margin-top: 0.5rem;
            }

            .dropdown-item {
                border-radius: 8px;
                padding: 0.5rem 1rem;
                transition: all 0.2s ease;
            }

            .dropdown-item:hover {
                background: var(--primary-gradient);
                color: white;
            }

            .card {
                border: none;
                border-radius: 16px;
                box-shadow: var(--shadow-sm);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .card:hover {
                transform: translateY(-4px);
                box-shadow: var(--shadow-lg);
            }

            .hero-section {
                background: var(--primary-gradient);
                color: white;
                padding: 6rem 0;
            }

            .search-section {
                background: white;
                box-shadow: var(--shadow-sm);
                border-bottom: 1px solid #e5e7eb;
                padding: 2rem 0;
            }

            .category-btn {
                padding: 0.75rem 1.5rem;
                border-radius: 50px;
                border: 2px solid #e5e7eb;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                margin: 0.25rem;
            }

            .category-btn:hover {
                border-color: #6b7280;
                transform: translateY(-2px);
            }

            .category-btn.active {
                background: var(--primary-gradient);
                color: white !important;
                border-color: transparent;
            }

            .property-card {
                transition: all 0.3s ease;
                height: 100%;
            }

            .property-card:hover {
                transform: translateY(-8px);
                box-shadow: var(--shadow-xl);
            }

            .status-badge {
                padding: 0.25rem 0.75rem;
                border-radius: 50px;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .status-available {
                background-color: #10b981;
                color: white;
            }

            .status-in-use {
                background-color: #ef4444;
                color: white;
            }

            .status-reserved {
                background-color: #f59e0b;
                color: white;
            }

            .property-image {
                height: 200px;
                background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .btn-primary {
                background: var(--primary-gradient);
                border: none;
                border-radius: 8px;
                padding: 0.75rem 1.5rem;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
            }

            .btn-success {
                background: var(--success-gradient);
                border: none;
            }

            .form-control:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
            }

            footer {
                background: #1f2937;
                color: white;
                margin-top: auto;
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                    <i class="bi bi-building me-2" style="font-size: 1.75rem;"></i>
                    <span>RM of Stanley</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="bi bi-building me-1"></i> Properties
                            </a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('reservations.index') }}">
                                    <i class="bi bi-calendar-event me-1"></i> My Reservations
                                </a>
                            </li>
                            @if(auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-1"></i> Admin Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.properties.index') }}">
                                        <i class="bi bi-gear me-1"></i> Manage Properties
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                    <ul class="navbar-nav">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-light text-dark ms-2" href="{{ route('register') }}" style="border-radius: 10px;">
                                    <i class="bi bi-person-plus me-1"></i> Register
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-2" style="font-size: 1.25rem;"></i>
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                                            <i class="bi bi-person me-2"></i> My Profile
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="hero-section">
            <div class="container">
                <div class="text-center">
                    <h1 class="display-4 fw-bold mb-4">
                        Find Your Perfect Space
                    </h1>
                    <p class="lead mb-5">
                        Reserve parks, conference rooms, and equipment for your next event
                    </p>
                    
                    <!-- Stats -->
                    <div class="row justify-content-center mb-5">
                        <div class="col-md-2 col-4">
                            <div class="h3 fw-bold">{{ $totalProperties ?? 0 }}</div>
                            <div class="small opacity-75">Available Properties</div>
                        </div>
                        <div class="col-md-2 col-4">
                            <div class="h3 fw-bold">{{ $availableNow ?? 0 }}</div>
                            <div class="small opacity-75">Available Now</div>
                        </div>
                        <div class="col-md-2 col-4">
                            <div class="h3 fw-bold">24/7</div>
                            <div class="small opacity-75">Booking Support</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filters Section -->
        <div class="search-section">
            <div class="container">
                <form method="GET" action="{{ route('home') }}">
                    <!-- Main Search Bar -->
                    <div class="row mb-4">
                        <div class="col-md-10">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}"
                                    placeholder="Search properties, locations..." 
                                    class="form-control"
                                >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                Search
                            </button>
                        </div>
                    </div>

                    <!-- Category Filter Buttons -->
                    <div class="mb-4">
                        <a href="{{ route('home') }}" 
                           class="category-btn {{ !request('type') ? 'active' : '' }}">
                            All Categories
                        </a>
                        @foreach(['Park', 'Conference Room', 'Equipment'] as $type)
                            <a href="{{ route('home', array_merge(request()->query(), ['type' => $type])) }}" 
                               class="category-btn {{ request('type') === $type ? 'active' : '' }}">
                                <i class="bi {{ $type === 'Park' ? 'bi-tree' : ($type === 'Conference Room' ? 'bi-people' : 'bi-tools') }} me-2"></i>
                                {{ $type }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Advanced Filters -->
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Min Capacity</label>
                            <input 
                                type="number" 
                                name="capacity" 
                                value="{{ request('capacity') }}"
                                placeholder="e.g. 10" 
                                class="form-control"
                            >
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Min Price/Hour</label>
                            <input 
                                type="number" 
                                name="min_price" 
                                value="{{ request('min_price') }}"
                                placeholder="$0" 
                                step="0.01"
                                class="form-control"
                            >
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Max Price/Hour</label>
                            <input 
                                type="number" 
                                name="max_price" 
                                value="{{ request('max_price') }}"
                                placeholder="$1000" 
                                step="0.01"
                                class="form-control"
                            >
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Sort By</label>
                            <select name="sort_by" onchange="this.form.submit()" class="form-select">
                                <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Name</option>
                                <option value="price_per_hour" {{ request('sort_by') === 'price_per_hour' ? 'selected' : '' }}>Price</option>
                                <option value="capacity" {{ request('sort_by') === 'capacity' ? 'selected' : '' }}>Capacity</option>
                            </select>
                        </div>
                    </div>

                    @if(request()->hasAny(['search', 'type', 'capacity', 'min_price', 'max_price']))
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-3">Active filters:</span>
                            <a href="{{ route('home') }}" class="text-decoration-none">
                                Clear all filters
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Properties Grid -->
        <div class="container my-5">
            @if($properties->count() > 0)
                <div class="mb-4">
                    <h2 class="h3 fw-bold mb-2">
                        Available Properties
                        <span class="text-muted fw-normal">({{ $properties->total() }} found)</span>
                    </h2>
                </div>

                <div class="row g-4">
                    @foreach($properties as $property)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="card property-card">
                                <!-- Property Image -->
                                <div class="property-image position-relative overflow-hidden">
                                    @if($property->image)
                                        <img src="{{ $property->image }}" 
                                             alt="{{ $property->name }}" 
                                             class="w-100 h-100" 
                                             style="object-fit: cover;">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                            <i class="bi {{ $property->type === 'Park' ? 'bi-tree' : ($property->type === 'Conference Room' ? 'bi-people' : 'bi-tools') }}" style="font-size: 3rem; color: #9ca3af;"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Status Badge Overlay -->
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="status-badge status-{{ $property->current_status === 'available' ? 'available' : ($property->current_status === 'in_use' ? 'in-use' : 'reserved') }}">
                                            {{ ucfirst(str_replace('_', ' ', $property->current_status)) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <!-- Property Header -->
                                    <div class="mb-3">
                                        <h5 class="card-title mb-1 fw-bold">
                                            {{ $property->name }}
                                        </h5>
                                        
                                        <!-- Property Type -->
                                        <div class="text-muted small mb-2">
                                            <i class="bi {{ $property->type === 'Park' ? 'bi-tree' : ($property->type === 'Conference Room' ? 'bi-people' : 'bi-tools') }} me-1"></i>
                                            {{ $property->type }}
                                        </div>

                                        <!-- Location -->
                                        @if($property->location)
                                            <div class="text-muted small mb-2">
                                                <i class="bi bi-geo-alt me-1"></i>
                                                {{ $property->location }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Property Details -->
                                    <div class="d-flex justify-content-between align-items-center text-muted small mb-3">
                                        @if($property->capacity)
                                            <div>
                                                <i class="bi bi-people me-1"></i>
                                                {{ $property->capacity }} people
                                            </div>
                                        @endif
                                        
                                        @if($property->price_per_hour)
                                            <div class="fw-bold text-dark">
                                               @if($property->price_per_hour == 0)
                                                    <span class="status-badge status-available">Free</span>
                                                  @else
                                                   ${{ number_format($property->price_per_hour, 2) }}/hr
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Description -->
                                    @if($property->description)
                                        <p class="card-text small text-muted mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ $property->description }}
                                        </p>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('properties.show', $property) }}" 
                                           class="btn btn-primary flex-fill">
                                            View Details
                                        </a>
                                        @auth
                                            <!-- @if($property->current_status === 'available')
                                                <button class="btn btn-success">
                                                    Book Now
                                                </button>
                                            @endif -->
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $properties->links() }}
                </div>
            @else
                <!-- No Results -->
                <div class="text-center py-5">
                    <i class="bi bi-search" style="font-size: 4rem; color: #d1d5db;"></i>
                    <h3 class="mt-4 mb-2">No properties found</h3>
                    <p class="text-muted mb-4">Try adjusting your search criteria or browse all categories.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        Show All Properties
                    </a>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <footer class="mt-5 py-4" style="background-color: #f8f9fa;">
            <div class="container">
                <div class="text-center">
                    <p class="mb-0 text-muted">&copy; {{ date('Y') }} RM of Stanley. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
