@extends('layouts.app')

@section('title', 'Create Reservation - RM of Stanley')

@section('styles')
<style>
    /* Enhanced form styling for better UX */
    .form-control:focus {
        border-color: #007bff !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    
    .input-group .form-control:focus {
        z-index: 5;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .page-header {
        background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(248,249,250,0.8) 100%);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .quick-duration:hover {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
        color: white !important;
    }
    
    /* Improved mobile responsiveness */
    @media (max-width: 768px) {
        .input-group .btn {
            border-radius: 12px !important;
            margin-top: 8px;
        }
        
        .input-group .form-control {
            border-radius: 12px !important;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header mb-4">
    <a href="{{ route('properties.show', $property) }}" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-2"></i> Back to Property
    </a>
    <h1><i class="bi bi-calendar-event me-2"></i>Reserve: {{ $property->name }}</h1>
    <p class="text-muted mb-0">Fill out the form below to request a reservation</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Reservation Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('reservations.store', $property) }}" method="POST">
                    @csrf
                    
                    <!-- Server-side Availability Error -->
                    @if(session('show_availability_error') && $errors->has('availability'))
                        <div class="alert alert-danger border-0 mb-4" style="border-radius: 12px; background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-x-circle me-2" style="font-size: 1.2rem; color: #d32f2f;"></i>
                                <div class="flex-grow-1">
                                    <strong style="color: #d32f2f;">❌ Property Not Available</strong>
                                    <p class="mb-2" style="color: #c62828;">{{ $errors->first('availability') }}</p>
                                    
                                    @if(session('conflicts') && session('conflicts')->count() > 0)
                                        <h6 style="color: #d32f2f; font-size: 0.9rem; margin-bottom: 8px;">Conflicting Reservations:</h6>
                                        <div class="mb-3" style="font-size: 0.85rem;">
                                            @foreach(session('conflicts') as $conflict)
                                                <div class="mb-1 p-2 bg-white rounded border">
                                                    <small>
                                                        <strong>{{ $conflict['contact'] }}</strong> - {{ $conflict['start_time'] }} to {{ $conflict['end_time'] }}
                                                        @if($conflict['purpose'])
                                                            <br><em>"{{ $conflict['purpose'] }}"</em>
                                                        @endif
                                                    </small>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    @if(session('suggestions') && count(session('suggestions')) > 0)
                                        <h6 style="color: #f57c00; font-size: 0.9rem; margin-bottom: 8px;">💡 Suggested Alternative Times:</h6>
                                        <div class="row">
                                            @foreach(session('suggestions') as $suggestion)
                                                <div class="col-md-6 mb-2">
                                                    <button type="button" class="btn btn-outline-warning w-100 server-suggestion-btn" 
                                                            data-start="{{ $suggestion['start_time'] }}" 
                                                            data-end="{{ $suggestion['end_time'] }}"
                                                            style="border-radius: 8px; font-size: 0.85rem;">
                                                        <div class="d-flex flex-column align-items-start">
                                                            <strong>{{ $suggestion['day_name'] }}</strong>
                                                            <small>{{ $suggestion['display'] }}</small>
                                                        </div>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label fw-semibold">
                                <i class="bi bi-calendar-event me-2"></i>Start Date & Time *
                            </label>
                            <div class="input-group">
                                <input type="datetime-local" class="form-control form-control-lg @error('start_time') is-invalid @enderror" 
                                       id="start_time" name="start_time" 
                                       value="{{ old('start_time') }}" 
                                       min="{{ now()->format('Y-m-d\TH:i') }}"
                                       style="border-radius: 12px; border-width: 2px; padding: 12px 16px;"
                                       required>
                                <button type="button" class="btn btn-outline-secondary" id="quickStartToday" style="border-radius: 0 12px 12px 0;">
                                    <i class="bi bi-calendar-day"></i> Today
                                </button>
                            </div>
                            @error('start_time')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>Select when your reservation begins
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label fw-semibold">
                                <i class="bi bi-calendar-x me-2"></i>End Date & Time *
                            </label>
                            <div class="input-group">
                                <input type="datetime-local" class="form-control form-control-lg @error('end_time') is-invalid @enderror" 
                                       id="end_time" name="end_time" 
                                       value="{{ old('end_time') }}" 
                                       min="{{ now()->format('Y-m-d\TH:i') }}"
                                       style="border-radius: 12px; border-width: 2px; padding: 12px 16px;"
                                       required>
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" style="border-radius: 0 12px 12px 0;">
                                    <i class="bi bi-clock"></i> +
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item quick-duration" href="#" data-hours="1">+1 Hour</a></li>
                                    <li><a class="dropdown-item quick-duration" href="#" data-hours="2">+2 Hours</a></li>
                                    <li><a class="dropdown-item quick-duration" href="#" data-hours="4">+4 Hours</a></li>
                                    <li><a class="dropdown-item quick-duration" href="#" data-hours="8">+8 Hours (Full Day)</a></li>
                                </ul>
                            </div>
                            @error('end_time')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>Select when your reservation ends
                            </small>
                        </div>
                    </div>

                    <div class="alert alert-primary d-none border-0" id="duration-alert" style="border-radius: 12px; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-hourglass-split me-2" style="font-size: 1.2rem; color: #1976d2;"></i>
                            <div>
                                <strong style="color: #1976d2;">Duration:</strong> 
                                <span id="duration-text" style="color: #1565c0; font-weight: 600;"></span>
                                <span id="estimated-cost" class="ms-2" style="color: #0d47a1; font-weight: 700;"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Availability Status -->
                    <div id="availability-container" class="d-none">
                        <!-- Available Status -->
                        <div class="alert alert-success border-0 d-none" id="available-alert" style="border-radius: 12px; background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle me-2" style="font-size: 1.2rem; color: #2e7d32;"></i>
                                <div>
                                    <strong style="color: #2e7d32;">✅ Property Available!</strong>
                                    <p class="mb-0" style="color: #388e3c;">The property is available for your selected time period.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Not Available Status -->
                        <div class="alert alert-danger border-0 d-none" id="unavailable-alert" style="border-radius: 12px; background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-x-circle me-2" style="font-size: 1.2rem; color: #d32f2f;"></i>
                                <div class="flex-grow-1">
                                    <strong style="color: #d32f2f;">❌ Property Not Available</strong>
                                    <p class="mb-2" style="color: #c62828;">The property is already booked during your selected time.</p>
                                    
                                    <!-- Conflicting Reservations -->
                                    <div id="conflicts-container" class="d-none">
                                        <h6 style="color: #d32f2f; font-size: 0.9rem; margin-bottom: 8px;">Conflicting Reservations:</h6>
                                        <div id="conflicts-list" style="font-size: 0.85rem;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alternative Times -->
                        <div class="alert alert-warning border-0 d-none" id="suggestions-alert" style="border-radius: 12px; background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-lightbulb me-2" style="font-size: 1.2rem; color: #f57c00;"></i>
                                <div class="flex-grow-1">
                                    <strong style="color: #f57c00;">💡 Suggested Alternative Times</strong>
                                    <p class="mb-2" style="color: #ef6c00;">Here are some available time slots with the same duration:</p>
                                    <div id="suggestions-list" class="row"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Loading Status -->
                        <div class="alert alert-info border-0 d-none" id="checking-alert" style="border-radius: 12px; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                            <div class="d-flex align-items-center">
                                <div class="spinner-border spinner-border-sm me-2" role="status" style="color: #1976d2;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div>
                                    <strong style="color: #1976d2;">Checking Availability...</strong>
                                    <p class="mb-0" style="color: #1565c0;">Please wait while we verify the property availability.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @guest
                    <!-- Guest Contact Information -->
                    <div class="card mb-4" style="border: 2px solid #e3f2fd; background: linear-gradient(135deg, #f8fcff 0%, #f0f8ff 100%);">
                        <div class="card-header" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border-bottom: 1px solid #90caf9;">
                            <h6 class="mb-0" style="color: #1976d2;">
                                <i class="bi bi-person-plus me-2"></i>Contact Information
                            </h6>
                            <small class="text-muted">Please provide your contact details for the reservation</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="guest_name" class="form-label fw-semibold">
                                        <i class="bi bi-person me-2"></i>Full Name *
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('guest_name') is-invalid @enderror" 
                                           id="guest_name" name="guest_name" 
                                           value="{{ old('guest_name') }}" 
                                           style="border-radius: 12px; border-width: 2px; padding: 12px 16px;"
                                           placeholder="Enter your full name"
                                           required>
                                    @error('guest_name')
                                        <div class="invalid-feedback">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="guest_email" class="form-label fw-semibold">
                                        <i class="bi bi-envelope me-2"></i>Email Address *
                                    </label>
                                    <input type="email" class="form-control form-control-lg @error('guest_email') is-invalid @enderror" 
                                           id="guest_email" name="guest_email" 
                                           value="{{ old('guest_email') }}" 
                                           style="border-radius: 12px; border-width: 2px; padding: 12px 16px;"
                                           placeholder="Enter your email address"
                                           required>
                                    @error('guest_email')
                                        <div class="invalid-feedback">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="guest_phone" class="form-label fw-semibold">
                                        <i class="bi bi-telephone me-2"></i>Phone Number *
                                    </label>
                                    <input type="tel" class="form-control form-control-lg @error('guest_phone') is-invalid @enderror" 
                                           id="guest_phone" name="guest_phone" 
                                           value="{{ old('guest_phone') }}" 
                                           style="border-radius: 12px; border-width: 2px; padding: 12px 16px;"
                                           placeholder="Enter your phone number"
                                           required>
                                    @error('guest_phone')
                                        <div class="invalid-feedback">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="guest_organization" class="form-label fw-semibold">
                                        <i class="bi bi-building me-2"></i>Organization (Optional)
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('guest_organization') is-invalid @enderror" 
                                           id="guest_organization" name="guest_organization" 
                                           value="{{ old('guest_organization') }}" 
                                           style="border-radius: 12px; border-width: 2px; padding: 12px 16px;"
                                           placeholder="Enter organization name">
                                    @error('guest_organization')
                                        <div class="invalid-feedback">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="alert alert-info border-0" style="background: linear-gradient(135deg, #e8f4fd 0%, #bee8ff 100%);">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-info-circle me-2 mt-1" style="color: #0ea5e9; font-size: 1.1rem;"></i>
                                    <div>
                                        <strong style="color: #0284c7;">Want to manage your reservations?</strong>
                                        <p class="mb-2" style="color: #0369a1;">
                                            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color: #0284c7;">Create a free account</a> 
                                            to easily track and manage all your reservations in one place.
                                        </p>
                                        <small style="color: #075985;">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color: #0284c7;">Sign in here</a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endguest

                    <div class="mb-4">
                        <label for="purpose" class="form-label fw-semibold">
                            <i class="bi bi-chat-text me-2"></i>Purpose (Optional)
                        </label>
                        <textarea class="form-control form-control-lg @error('purpose') is-invalid @enderror" 
                                  id="purpose" name="purpose" rows="3" 
                                  style="border-radius: 12px; border-width: 2px; padding: 12px 16px; resize: vertical;"
                                  placeholder="Describe the purpose of your reservation...">{{ old('purpose') }}</textarea>
                        @error('purpose')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>Let us know what you'll be using the property for
                        </small>
                    </div>

                    <div class="d-grid gap-2">
                        @auth
                            <button type="submit" class="btn btn-warning btn-lg" style="border-radius: 12px; padding: 16px; font-weight: 600;">
                                <i class="bi bi-check-circle me-2"></i> Submit Reservation Request
                            </button>
                        @else
                            <button type="submit" class="btn btn-warning btn-lg" style="border-radius: 12px; padding: 16px; font-weight: 600;">
                                <i class="bi bi-send me-2"></i> Submit Guest Reservation Request
                            </button>
                            <small class="text-muted text-center mt-2">
                                <i class="bi bi-shield-check me-1"></i>
                                We'll send a confirmation to your email address
                            </small>
                        @endauth
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card" style="position: sticky; top: 2rem; z-index: 10;">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i>Property Information</h5>
            </div>
            <div class="card-body">
                <h6 class="fw-bold mb-2">{{ $property->name }}</h6>
                <span class="badge mb-3" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white;">
                    {{ $property->type }}
                </span>
                
                @if($property->location)
                    <p class="text-muted mb-2">
                        <i class="bi bi-geo-alt-fill me-2"></i>{{ $property->location }}
                    </p>
                @endif

                @if($property->price_per_hour)
                    <div class="mb-3 p-2 rounded" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <small class="text-muted d-block">Price per Hour</small>
                        <strong class="text-dark">${{ number_format($property->price_per_hour, 2) }}</strong>
                    </div>
                @endif

                @if($property->capacity)
                    <div class="mb-3">
                        <i class="bi bi-people-fill me-2"></i>
                        <small class="text-muted">Capacity: <strong>{{ $property->capacity }} people</strong></small>
                    </div>
                @endif

                <div class="mt-3">
                    @php
                        $status = $property->current_status;
                    @endphp
                    @if($status === 'available')
                        <span class="status-badge status-available">
                            <i class="bi bi-check-circle"></i> Available
                        </span>
                    @else
                        <span class="status-badge status-reserved">
                            <i class="bi bi-exclamation-triangle"></i> May not be available
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        const durationAlert = document.getElementById('duration-alert');
        const durationText = document.getElementById('duration-text');
        const estimatedCost = document.getElementById('estimated-cost');
        const quickStartToday = document.getElementById('quickStartToday');
        const quickDurationButtons = document.querySelectorAll('.quick-duration');
        
        // Property price per hour (if available)
        const pricePerHour = Number('{{ $property->price_per_hour ?? 0 }}');

        // Quick start today button
        quickStartToday.addEventListener('click', function() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            
            startTimeInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
            updateEndTimeMin();
            calculateDuration();
        });

        // Quick duration buttons
        quickDurationButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                if (startTimeInput.value) {
                    const startDate = new Date(startTimeInput.value);
                    const hours = parseInt(this.dataset.hours);
                    const endDate = new Date(startDate.getTime() + (hours * 60 * 60 * 1000));
                    
                    const year = endDate.getFullYear();
                    const month = String(endDate.getMonth() + 1).padStart(2, '0');
                    const day = String(endDate.getDate()).padStart(2, '0');
                    const endHours = String(endDate.getHours()).padStart(2, '0');
                    const endMinutes = String(endDate.getMinutes()).padStart(2, '0');
                    
                    endTimeInput.value = `${year}-${month}-${day}T${endHours}:${endMinutes}`;
                    calculateDuration();
                } else {
                    // Show an alert to select start time first
                    const alertElement = document.createElement('div');
                    alertElement.className = 'alert alert-warning alert-dismissible fade show mt-2';
                    alertElement.innerHTML = `
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Please select a start time first.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    endTimeInput.parentElement.parentElement.appendChild(alertElement);
                    
                    setTimeout(() => {
                        if (alertElement.parentElement) {
                            alertElement.remove();
                        }
                    }, 3000);
                }
            });
        });

        function updateEndTimeMin() {
            if (startTimeInput.value) {
                endTimeInput.min = startTimeInput.value;
                if (endTimeInput.value && endTimeInput.value <= startTimeInput.value) {
                    endTimeInput.value = '';
                }
            }
        }

        function calculateDuration() {
            const start = new Date(startTimeInput.value);
            const end = new Date(endTimeInput.value);

            if (start && end && end > start) {
                const diffMs = end - start;
                const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
                const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

                if (diffHours > 0 || diffMinutes > 0) {
                    let duration = '';
                    if (diffHours > 0) {
                        duration += diffHours + ' ' + (diffHours === 1 ? 'hour' : 'hours');
                    }
                    if (diffMinutes > 0) {
                        if (duration) duration += ' and ';
                        duration += diffMinutes + ' ' + (diffMinutes === 1 ? 'minute' : 'minutes');
                    }
                    durationText.textContent = duration;
                    
                    // Calculate estimated cost if price per hour is available
                    if (pricePerHour > 0) {
                        const totalHours = diffHours + (diffMinutes / 60);
                        const totalCost = totalHours * pricePerHour;
                        estimatedCost.innerHTML = `| <strong>Estimated Cost: $${totalCost.toFixed(2)}</strong>`;
                    }
                    
                    durationAlert.classList.remove('d-none');
                } else {
                    durationAlert.classList.add('d-none');
                }
            } else {
                durationAlert.classList.add('d-none');
            }
        }

        // Update end time minimum when start time changes
        startTimeInput.addEventListener('change', function() {
            updateEndTimeMin();
            calculateDuration();
            checkAvailability();
        });

        endTimeInput.addEventListener('change', function() {
            calculateDuration();
            checkAvailability();
        });

        // Availability checking functionality
        function checkAvailability() {
            const startTime = startTimeInput.value;
            const endTime = endTimeInput.value;
            
            if (!startTime || !endTime) {
                hideAvailabilityAlerts();
                return;
            }

            const availabilityContainer = document.getElementById('availability-container');
            const checkingAlert = document.getElementById('checking-alert');
            
            // Show loading state
            hideAvailabilityAlerts();
            availabilityContainer.classList.remove('d-none');
            checkingAlert.classList.remove('d-none');

            // Make API request to check availability
            fetch(`{{ route('api.properties.availability', $property) }}?start_time=${encodeURIComponent(startTime)}&end_time=${encodeURIComponent(endTime)}`)
                .then(response => response.json())
                .then(data => {
                    checkingAlert.classList.add('d-none');
                    
                    if (data.available) {
                        showAvailableAlert();
                    } else {
                        showUnavailableAlert(data.conflicts, data.suggestions);
                    }
                })
                .catch(error => {
                    console.error('Error checking availability:', error);
                    checkingAlert.classList.add('d-none');
                    // Hide availability container on error
                    availabilityContainer.classList.add('d-none');
                });
        }

        function hideAvailabilityAlerts() {
            document.getElementById('available-alert').classList.add('d-none');
            document.getElementById('unavailable-alert').classList.add('d-none');
            document.getElementById('suggestions-alert').classList.add('d-none');
            document.getElementById('checking-alert').classList.add('d-none');
            document.getElementById('conflicts-container').classList.add('d-none');
        }

        function showAvailableAlert() {
            document.getElementById('available-alert').classList.remove('d-none');
            
            // Enable submit button
            const submitButton = document.querySelector('button[type="submit"]');
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="bi bi-calendar-check me-2"></i>Submit Reservation Request';
            submitButton.className = 'btn btn-success btn-lg';
        }

        function showUnavailableAlert(conflicts, suggestions) {
            const unavailableAlert = document.getElementById('unavailable-alert');
            const conflictsContainer = document.getElementById('conflicts-container');
            const conflictsList = document.getElementById('conflicts-list');
            const suggestionsAlert = document.getElementById('suggestions-alert');
            const suggestionsList = document.getElementById('suggestions-list');
            
            unavailableAlert.classList.remove('d-none');
            
            // Show conflicts if any
            if (conflicts && conflicts.length > 0) {
                conflictsContainer.classList.remove('d-none');
                conflictsList.innerHTML = '';
                
                conflicts.forEach(conflict => {
                    const conflictItem = document.createElement('div');
                    conflictItem.className = 'mb-1 p-2 bg-white rounded border';
                    conflictItem.innerHTML = `
                        <small>
                            <strong>${conflict.contact}</strong> - ${conflict.start_time} to ${conflict.end_time}
                            ${conflict.purpose ? `<br><em>"${conflict.purpose}"</em>` : ''}
                        </small>
                    `;
                    conflictsList.appendChild(conflictItem);
                });
            }
            
            // Show suggestions if any
            if (suggestions && suggestions.length > 0) {
                suggestionsAlert.classList.remove('d-none');
                suggestionsList.innerHTML = '';
                
                suggestions.forEach(suggestion => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.className = 'col-md-6 mb-2';
                    suggestionItem.innerHTML = `
                        <button type="button" class="btn btn-outline-warning w-100 suggestion-btn" 
                                data-start="${suggestion.start_time}" 
                                data-end="${suggestion.end_time}"
                                style="border-radius: 8px; font-size: 0.85rem;">
                            <div class="d-flex flex-column align-items-start">
                                <strong>${suggestion.day_name}</strong>
                                <small>${suggestion.display}</small>
                            </div>
                        </button>
                    `;
                    suggestionsList.appendChild(suggestionItem);
                });
                
                // Add click handlers for suggestion buttons
                document.querySelectorAll('.suggestion-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        startTimeInput.value = this.dataset.start;
                        endTimeInput.value = this.dataset.end;
                        calculateDuration();
                        checkAvailability();
                        
                        // Scroll to top of form
                        startTimeInput.scrollIntoView({ behavior: 'smooth' });
                    });
                });
            }
            
            // Disable submit button
            const submitButton = document.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="bi bi-x-circle me-2"></i>Cannot Submit - Property Not Available';
            submitButton.className = 'btn btn-danger btn-lg';
        }
        
        // Add visual feedback for form validation
        const form = startTimeInput.closest('form');
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Processing...';
            submitButton.disabled = true;
        });
        
        // Auto-focus improvements
        startTimeInput.addEventListener('focus', function() {
            this.style.borderColor = '#007bff';
            this.style.boxShadow = '0 0 0 0.2rem rgba(0, 123, 255, 0.25)';
        });
        
        startTimeInput.addEventListener('blur', function() {
            this.style.borderColor = '';
            this.style.boxShadow = '';
        });
        
        endTimeInput.addEventListener('focus', function() {
            this.style.borderColor = '#007bff';
            this.style.boxShadow = '0 0 0 0.2rem rgba(0, 123, 255, 0.25)';
        });
        
        endTimeInput.addEventListener('blur', function() {
            this.style.borderColor = '';
            this.style.boxShadow = '';
        });
        
        // Handle server-side suggestion buttons
        document.querySelectorAll('.server-suggestion-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                startTimeInput.value = this.dataset.start;
                endTimeInput.value = this.dataset.end;
                calculateDuration();
                checkAvailability();
                
                // Hide the server error alert
                const serverError = document.querySelector('.alert-danger');
                if (serverError) {
                    serverError.style.display = 'none';
                }
                
                // Scroll to top of form
                startTimeInput.scrollIntoView({ behavior: 'smooth' });
            });
        });
    });
</script>
@endsection

