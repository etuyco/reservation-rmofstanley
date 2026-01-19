@extends('layouts.app')

@section('title', $property->name . ' - RM of Stanley')

@section('content')
<div class="page-header mb-4">
    <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-2"></i> Back to Properties
    </a>
    <div class="d-flex justify-content-between align-items-start flex-wrap">
        <div>
            <h1>{{ $property->name }}</h1>
            <div class="d-flex align-items-center gap-3 flex-wrap mt-2">
                <span class="badge" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white; padding: 0.5rem 1rem; font-size: 0.875rem;">
                    {{ $property->type }}
                </span>
                @if($property->location)
                    <span class="text-muted">
                        <i class="bi bi-geo-alt-fill me-1"></i>{{ $property->location }}
                    </span>
                @endif
            </div>
        </div>
        @php
            $status = $property->current_status;
        @endphp
        @if($status === 'available')
            <span class="status-badge status-available">
                <i class="bi bi-check-circle"></i> Available
            </span>
        @elseif($status === 'in_use')
            <span class="status-badge status-in-use">
                <i class="bi bi-x-circle"></i> Currently In Use
            </span>
        @elseif($status === 'reserved')
            <span class="status-badge status-reserved">
                <i class="bi bi-clock"></i> Currently Reserved
            </span>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Property Details Card -->
        <div class="card mb-4">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: black;">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Property Details</h5>
            </div>
            <div class="card-body">
                @if($property->description)
                    <div class="mb-4">
                        <p class="card-text lead" style="line-height: 1.8; font-size: 1.1rem; color: #374151;">{{ $property->description }}</p>
                    </div>
                @endif
                
                <!-- Key Information Grid -->
                <div class="row g-3 mb-4">
                    <!-- Category & Type -->
                    @if($property->category || $property->type)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3 h-100" style="background: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%); border: 1px solid #b3d9ff;">
                                <div class="me-3">
                                    @if($property->category && $property->category->icon)
                                        <i class="{{ $property->category->icon }}" style="font-size: 2rem; color: {{ $property->category->color ?? '#3b82f6' }};"></i>
                                    @else
                                        <i class="bi bi-building" style="font-size: 2rem; color: #3b82f6;"></i>
                                    @endif
                                </div>
                                <div>
                                    <small class="text-muted d-block fw-medium">Category</small>
                                    <strong style="font-size: 1.1rem; color: #1f2937;">
                                        {{ $property->category ? $property->category->name : $property->type }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Location -->
                    @if($property->location)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3 h-100" style="background: linear-gradient(135deg, #fefce8 0%, #fef3c7 100%); border: 1px solid #fde68a;">
                                <div class="me-3">
                                    <i class="bi bi-geo-alt-fill" style="font-size: 2rem; color: #f59e0b;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block fw-medium">Location</small>
                                    <strong style="font-size: 1.1rem; color: #1f2937;">{{ $property->location }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Detailed Information Grid -->
                <div class="row g-3">
                    @if($property->capacity)
                        <div class="col-sm-6 col-lg-3">
                            <div class="text-center p-1 rounded-3" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e5e7eb;">
                                <i class="bi bi-people-fill d-block mb-2" style="font-size: 2.5rem; color: #3b82f6;"></i>
                                <small class="text-muted d-block">Capacity</small>
                                <strong class="d-block" style="font-size: 1.2rem; color: #1f2937;">{{ $property->capacity }}</strong>
                                <small class="text-muted">people</small>
                            </div>
                        </div>
                    @endif
                    
                    <!-- <div class="col-sm-6 col-lg-3">
                        <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #bbf7d0;">
                            <i class="bi bi-currency-dollar d-block mb-2" style="font-size: 2.5rem; color: #10b981;"></i>
                            <small class="text-muted d-block">Hourly Rate</small>
                            <strong class="d-block" style="font-size: 1.2rem; color: #1f2937;">
                                @if($property->price_per_hour == 0 || $property->price_per_hour === null)
                                    <span class="text-success">FREE</span>
                                @else
                                    ${{ number_format($property->price_per_hour, 2) }}
                                @endif
                            </strong>
                        </div>
                    </div> -->
                    
                    @if($property->max_daily_booking_days)
                        <div class="col-sm-6 col-lg-3">
                            <div class="text-center p-1 rounded-3" style="background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%); border: 1px solid #f9a8d4;">
                                <i class="bi bi-calendar-date d-block mb-2" style="font-size: 2.5rem; color: #ec4899;"></i>
                                <small class="text-muted d-block">Max Days</small>
                                <strong class="d-block" style="font-size: 1.2rem; color: #1f2937;">{{ $property->max_daily_booking_days }}</strong>
                                <small class="text-muted">daily booking</small>
                            </div>
                        </div>
                    @endif
                    
                    <div class="col-sm-6 col-lg-6">
                        <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 1px solid #7dd3fc;">
                            @php
                                $status = $property->current_status ?? 'available';
                                $statusIcon = $status === 'available' ? 'bi-check-circle-fill' : ($status === 'in_use' ? 'bi-exclamation-circle-fill' : 'bi-clock-fill');
                                $statusColor = $status === 'available' ? '#10b981' : ($status === 'in_use' ? '#ef4444' : '#f59e0b');
                            @endphp
                            <i class="bi {{ $statusIcon }} d-block mb-2" style="font-size: 2.5rem; color: {{ $statusColor }};"></i>
                            <small class="text-muted d-block">Status</small>
                            <strong class="d-block" style="font-size: 1.2rem; color: #1f2937;">
                                {{ $status === 'available' ? 'Available' : ($status === 'in_use' ? 'In Use' : 'Reserved') }}
                            </strong>
                        </div>
                    </div>
                </div>
                
                
                <!-- Property ID for Admin Reference -->
                <div class="mt-4 pt-3 border-top">
                    <small class="text-muted">
                        <i class="bi bi-hash me-1"></i>Property ID: {{ $property->id }} 
                        @if($property->is_active)
                            <span class="badge bg-success ms-2" style="font-size: 0.7rem;">Active</span>
                        @else
                            <span class="badge bg-secondary ms-2" style="font-size: 0.7rem;">Inactive</span>
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <!-- Availability Calendar Card -->
        <div class="card">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: black;">
                <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Availability Calendar</h5>
            </div>
            <div class="card-body">
                <!-- Booking Type Selector -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-primary mb-3">
                        <i class="bi bi-clock-history me-2"></i>Choose Your Booking Type
                    </label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="bookingType" id="hourlyBooking" value="hourly" checked>
                            <label class="btn btn-outline-primary w-100 d-flex flex-column align-items-center p-3 rounded-3" for="hourlyBooking" style="border: 2px solid #e5e7eb; transition: all 0.2s ease;">
                                <i class="bi bi-clock mb-2" style="font-size: 1.5rem;"></i>
                                <strong>Hourly Booking</strong>
                                <small class="text-muted mt-1">Select specific time slots</small>
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="bookingType" id="dailyBooking" value="daily">
                            <label class="btn btn-outline-primary w-100 d-flex flex-column align-items-center p-3 rounded-3" for="dailyBooking" style="border: 2px solid #e5e7eb; transition: all 0.2s ease;">
                                <i class="bi bi-calendar-day mb-2" style="font-size: 1.5rem;"></i>
                                <strong>Daily Booking</strong>
                                <small class="text-muted mt-1">Select full days</small>
                            </label>
                        </div>
                    </div>
                    <div class="mt-2 p-2 bg-light rounded-2">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            <span id="bookingTypeDescription">Select specific time slots for your reservation</span>
                        </small>
                    </div>
                </div>

                <!-- Date Navigation -->
                <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded-3">
                    <button class="btn btn-outline-secondary" id="prevDay" title="Previous Week">
                        <i class="bi bi-chevron-left"></i> <span class="d-none d-sm-inline" id="prevButtonText">Prev Week</span>
                    </button>
                    <div class="text-center">
                        <h6 class="mb-0 text-primary" id="dateRange"></h6>
                        <small class="text-muted" id="viewDescription">7-Day View</small>
                    </div>
                    <button class="btn btn-outline-secondary" id="nextDay" title="Next Week">
                        <span class="d-none d-sm-inline" id="nextButtonText">Next Week</span> <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                <!-- Quick Date Buttons -->
                <div class="d-flex gap-2 mb-4 justify-content-center">
                    <button class="btn btn-sm btn-outline-primary flex-fill" data-date-offset="0" style="max-width: 120px;">This Week</button>
                    <button class="btn btn-sm btn-outline-primary flex-fill" data-date-offset="7" style="max-width: 120px;">Next Week</button>
                    <button class="btn btn-sm btn-outline-primary flex-fill" data-date-offset="14" style="max-width: 120px;">Week After</button>
                </div>

                <!-- Daily Booking Interface -->
                <div id="dailyBookingInterface" style="display: none;">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted fw-medium">Select Days (Maximum {{ $property->max_daily_booking_days }} days allowed)</small>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-success" style="font-size: 0.7rem;">Available</span>
                                <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">Partial</span>
                                <span class="badge bg-danger" style="font-size: 0.7rem;">Booked</span>
                                <span class="badge bg-primary" style="font-size: 0.7rem;">Selected</span>
                            </div>
                        </div>
                        
                        <!-- Daily Calendar Grid -->
                        <div id="dailyCalendarGrid">
                            <!-- Daily calendar will be loaded here -->
                            <div class="text-center py-3">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div class="mt-2 small text-muted">Loading daily availability...</div>
                            </div>
                        </div>
                        
                        <!-- Selected Days Summary -->
                        <div id="selectedDaysSummary" class="mt-3" style="display: none;">
                            <div class="alert alert-info">
                                <i class="bi bi-calendar-check me-2"></i>
                                <strong>Selected Days:</strong> <span id="selectedDaysCount">0</span> day(s)
                                <div id="selectedDaysList" class="mt-1 small"></div>
                            </div>
                        </div>
                        
                        <!-- Maximum Days Warning -->
                        <div id="maxDaysWarning" class="mt-3" style="display: none;">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Maximum Limit:</strong> You can select up to {{ $property->max_daily_booking_days }} days for daily booking.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hourly 7-Day Calendar Grid -->
                <div id="hourlyBookingInterface">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted fw-medium">Time Slots (7 AM - 10 PM)</small>
                            <div class="d-flex gap-2">
                                <span class="badge bg-success" style="font-size: 0.7rem;">Available</span>
                                <span class="badge bg-danger" style="font-size: 0.7rem;">Booked</span>
                            </div>
                        </div>
                        
                        <!-- Calendar Grid -->
                        <div class="calendar-grid" id="calendarGrid">
                            <!-- Calendar will be loaded here -->
                            <div class="text-center py-3">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div class="mt-2 small text-muted">Loading availability...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Reservation Form -->
        <div class="card sticky-top" style="top: 2rem;">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); color: black;">
                <h5 class="mb-0"><i class="bi bi-lightning-charge me-2"></i>Quick Reservation</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-primary border-0 mb-3 rounded-3" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border: 1px solid #93c5fd !important;">
                    <div class="text-center">
                        <i class="bi bi-info-circle text-primary" style="font-size: 1.5rem;"></i>
                        <p class="mb-0 mt-2 text-primary fw-medium">
                            Select time slots or days below to start your reservation
                        </p>
                    </div>
                </div>

                <form id="quickReservationForm" action="{{ route('reservations.store', $property) }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="booking_type" id="bookingType" value="hourly">
                    <input type="hidden" name="start_time" id="selectedStartTime">
                    <input type="hidden" name="end_time" id="selectedEndTime">
                    <input type="hidden" name="selected_days" id="selectedDays">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-primary">
                            <i class="bi bi-calendar-check me-1"></i>Selected Time/Days
                        </label>
                        <div class="p-3 bg-light rounded-3 border" id="selectedTimeDisplay" style="font-family: monospace; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;">
                            No time/days selected
                        </div>
                    </div>

                    @guest
                    <div class="row g-2 mb-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">Guest Information</label>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" class="form-control border-start-0" name="guest_name" placeholder="Your Name" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" class="form-control border-start-0" name="guest_email" placeholder="Your Email" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone text-muted"></i></span>
                                <input type="tel" class="form-control border-start-0" name="guest_phone" placeholder="Your Phone" required>
                            </div>
                        </div>
                    </div>
                    @endguest

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Purpose of Reservation</label>
                        <textarea class="form-control" name="purpose" placeholder="Briefly describe the purpose of your reservation and how many people will be attending..." rows="3" required style="resize: vertical;"></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning btn-lg fw-semibold" id="submitReservation" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <i class="bi bi-calendar-plus me-2"></i> Make Reservation
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="cancelSelection">
                            <i class="bi bi-x-circle me-1"></i> Cancel Selection
                        </button>
                    </div>
                </form>

                @guest
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        Want to track your reservations? 
                        <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-primary">Create an account</a>
                    </small>
                </div>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Enhanced button styling for booking type selector */
.btn-check:checked + .btn-outline-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    border-color: #3b82f6 !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
}

.btn-outline-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Calendar styling improvements */
.calendar-grid {
    border: 1px solid #dee2e6;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.calendar-header {
    display: grid;
    grid-template-columns: 60px repeat(7, 1fr);
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-bottom: 2px solid #dee2e6;
}

.calendar-time-header {
    padding: 12px 8px;
    border-right: 1px solid #dee2e6;
    font-size: 0.8rem;
    font-weight: 600;
    color: #6c757d;
    text-align: center;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
}

.calendar-date-header.past-date {
    background: #f8f9fa;
    opacity: 0.6;
    color: #6c757d;
}

.calendar-date-header.today-date {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1d4ed8;
    font-weight: bold;
}

.calendar-date-header.past-date .date,
.calendar-date-header.past-date .day {
    color: #adb5bd;
}

.calendar-slot.past {
    background: #f8f9fa !important;
    color: #adb5bd !important;
    cursor: not-allowed;
    opacity: 0.5;
}

.calendar-slot.past:hover {
    background: #f8f9fa !important;
    color: #adb5bd !important;
    cursor: not-allowed;
}

.slot-past {
    color: #adb5bd;
    font-size: 0.7rem;
    font-style: italic;
}

.calendar-date-header {
    padding: 12px;
    border-right: 1px solid #dee2e6;
    text-align: center;
    font-weight: 600;
    color: #495057;
}

.calendar-date-header:last-child {
    border-right: none;
}

.calendar-date-header .date {
    display: block;
    font-size: 0.9rem;
}

.calendar-date-header .day {
    display: block;
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: normal;
}

.calendar-body {
    max-height: 500px;
    overflow-y: auto;
}

.calendar-row {
    display: grid;
    grid-template-columns: 60px repeat(7, 1fr);
    border-bottom: 1px solid #f0f0f0;
    min-height: 45px;
}

.calendar-row:last-child {
    border-bottom: none;
}

.calendar-time-cell {
    padding: 8px;
    border-right: 1px solid #f0f0f0;
    font-size: 0.75rem;
    color: #6c757d;
    text-align: center;
    background: linear-gradient(135deg, #fafafa 0%, #f1f5f9 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.calendar-slot {
    border-right: 1px solid #f0f0f0;
    padding: 3px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 45px;
    position: relative;
    font-size: 0.7rem;
}

.calendar-slot:last-child {
    border-right: none;
}

/* Responsive design for smaller screens */
@media (max-width: 768px) {
    .calendar-grid {
        font-size: 0.6rem;
    }
    
    .calendar-header {
        grid-template-columns: 45px repeat(7, 1fr);
    }
    
    .calendar-row {
        grid-template-columns: 45px repeat(7, 1fr);
        min-height: 35px;
    }
    
    .calendar-date-header {
        padding: 4px 2px;
        font-size: 0.65rem;
        min-height: 40px;
    }
    
    .calendar-slot {
        font-size: 0.6rem;
        min-height: 30px;
        padding: 2px;
    }
    
    .calendar-time-header,
    .calendar-time-cell {
        font-size: 0.6rem;
        padding: 4px 2px;
    }
}

.calendar-slot.available {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border-color: #bbf7d0;
}

.calendar-slot.available:hover {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    transform: scale(1.02);
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.calendar-slot.booked {
    background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
    border-color: #fca5a5;
    cursor: not-allowed;
    opacity: 0.8;
}

.calendar-slot.selected {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important;
    color: #000;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.4) !important;
}

.calendar-slot.selecting {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%) !important;
    color: #92400e;
}

.slot-content {
    text-align: center;
    font-size: 0.8rem;
    width: 100%;
}

.slot-available {
    color: #065f46;
    font-weight: 500;
}

.slot-booked {
    color: #dc2626;
    font-size: 0.7rem;
}

/* Daily booking styles */
.daily-calendar-container {
    border: 1px solid #dee2e6;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.daily-calendar-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 12px;
    border-bottom: 1px solid #dee2e6;
    text-align: center;
    font-weight: 600;
    color: #495057;
}

.daily-calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0;
}

.daily-calendar-cell {
    padding: 15px;
    border-right: 1px solid #f0f0f0;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
    background: white;
    min-height: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.daily-calendar-cell:hover {
    background: #f8f9fa;
}

.daily-calendar-cell.selected-day {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    color: white;
}

.daily-calendar-cell.booked-day {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
    cursor: not-allowed;
    opacity: 0.8;
}

.daily-calendar-cell.booked-day:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
}

.daily-calendar-cell.past-day {
    background: #f8f9fa;
    color: #adb5bd;
    cursor: not-allowed;
    opacity: 0.5;
}

.daily-calendar-cell.past-day:hover {
    background: #f8f9fa;
}

.daily-calendar-cell .day-name {
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 4px;
}

.daily-calendar-cell .day-number {
    font-size: 1.1rem;
    font-weight: bold;
}

.minimum-days-warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: 1px solid #f59e0b;
    color: #92400e;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 0.9rem;
    margin-top: 10px;
}

.selected-days-summary {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 8px;
    padding: 12px;
    margin-top: 10px;
}

@media (max-width: 768px) {
    .daily-calendar-cell {
        padding: 8px 4px;
        min-height: 45px;
        font-size: 0.8rem;
    }
    
    .daily-calendar-cell .day-name {
        font-size: 0.7rem;
    }
    
    .daily-calendar-cell .day-number {
        font-size: 0.9rem;
    }
}

/* Full Calendar Styles */
.full-calendar-container {
    border: 1px solid #dee2e6;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.calendar-month-header {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
    padding: 20px;
    text-align: center;
}

.calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-bottom: 2px solid #dee2e6;
}

.weekday-header {
    padding: 12px 8px;
    text-align: center;
    font-weight: 600;
    font-size: 0.9rem;
    color: #495057;
    border-right: 1px solid #dee2e6;
}

.weekday-header:last-child {
    border-right: none;
}

.calendar-days-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0;
}

.calendar-day-cell {
    min-height: 80px;
    padding: 8px;
    border-right: 1px solid #f0f0f0;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    background: white;
}

.calendar-day-cell:hover {
    background: #f8f9fa;
    transform: scale(1.02);
    z-index: 2;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.calendar-day-cell:nth-child(7n) {
    border-right: none;
}

.calendar-day-cell.other-month {
    background: #fafafa;
    color: #adb5bd;
    opacity: 0.5;
}

.calendar-day-cell.other-month:hover {
    background: #fafafa;
    transform: none;
    box-shadow: none;
    cursor: default;
}

.calendar-day-cell.past-day {
    background: #f8f9fa;
    color: #adb5bd;
    cursor: not-allowed;
    opacity: 0.6;
}

.calendar-day-cell.past-day:hover {
    background: #f8f9fa;
    transform: none;
    box-shadow: none;
}

.calendar-day-cell.available-day {
    background: white;
    color: #495057;
}

.calendar-day-cell.available-day:hover {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    border-color: #3b82f6;
}

.calendar-day-cell.partially-booked-day {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border-color: #f59e0b;
    cursor: not-allowed;
}

.calendar-day-cell.partially-booked-day:hover {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    transform: none;
    box-shadow: none;
}

.calendar-day-cell.fully-booked-day {
    background: linear-gradient(135deg, #fecaca 0%, #f87171 100%);
    color: #7f1d1d;
    cursor: not-allowed;
    border-color: #dc2626;
}

.calendar-day-cell.fully-booked-day:hover {
    background: linear-gradient(135deg, #fecaca 0%, #f87171 100%);
    transform: none;
    box-shadow: none;
}

.calendar-day-cell.selected-day {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    color: white !important;
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

.calendar-day-cell.selected-day:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%) !important;
    transform: scale(1.05);
}

.calendar-day-cell.today {
    border: 2px solid #10b981;
    font-weight: bold;
}

.day-number {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 4px;
}

.day-status {
    font-size: 0.7rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.booking-count {
    font-size: 0.65rem;
    background: rgba(0,0,0,0.1);
    padding: 2px 4px;
    border-radius: 3px;
    margin-top: 2px;
}

.today-indicator {
    font-size: 0.6rem;
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%);
    background: #10b981;
    color: white;
    padding: 1px 4px;
    border-radius: 2px;
}

@media (max-width: 768px) {
    .calendar-day-cell {
        min-height: 60px;
        padding: 4px;
    }
    
    .day-number {
        font-size: 0.9rem;
    }
    
    .day-status {
        font-size: 0.6rem;
    }
    
    .weekday-header {
        padding: 8px 4px;
        font-size: 0.8rem;
    }
    
    .calendar-month-header {
        padding: 12px;
    }
}

.conflict-info {
    font-size: 0.65rem;
    margin-top: 2px;
    opacity: 0.8;
}

.time-slots-container {
    max-height: 400px;
    overflow-y: auto;
}

.time-slot {
    display: block;
    width: 100%;
    padding: 8px 12px;
    margin: 2px 0;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background: white;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    font-size: 0.9rem;
}

.time-slot.available {
    border-color: #10b981;
    color: #065f46;
}

.time-slot.available:hover {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-color: #10b981;
}

.time-slot.booked {
    background: linear-gradient(135deg, #fecaca 0%, #f87171 100%);
    border-color: #dc2626;
    color: #7f1d1d;
    cursor: not-allowed;
    opacity: 0.7;
}

.time-slot.selected {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    border-color: #f59e0b;
    color: #000;
}

.time-slot.selecting {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-color: #f59e0b;
    color: #92400e;
}

.date-navigation {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
    padding: 12px;
}

/* Card hover effects */
.card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

/* Improved form styling */
.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.input-group-text {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-color: #e5e7eb;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Calendar script loaded and DOM ready');
    console.log('🔧 Testing element access...');
    console.log('📋 Elements check:', {
        hourlyInterface: !!document.getElementById('hourlyBookingInterface'),
        dailyInterface: !!document.getElementById('dailyBookingInterface'),
        hourlyRadio: !!document.getElementById('hourlyBooking'),
        dailyRadio: !!document.getElementById('dailyBooking'),
        dailyCalendarGrid: !!document.getElementById('dailyCalendarGrid')
    });
    
    const propertyId = {{ $property->id }};
    const maxDailyBookingDays = {{ $property->max_daily_booking_days }};
    
    // Get start of week (Monday) function
    function getStartOfWeek(date) {
        const d = new Date(date);
        const day = d.getDay();
        const diff = d.getDate() - day + (day === 0 ? -6 : 1); // Adjust when Sunday is 0
        return new Date(d.setDate(diff));
    }
    
    let currentStartDate = getStartOfWeek(new Date());
    let selectedStartSlot = null;
    let selectedEndSlot = null;
    let isSelecting = false;
    
    // Daily booking variables
    let selectedDays = [];
    let bookingMode = 'hourly';
    
    console.log('Property ID:', propertyId);
    console.log('🗓️  Initial currentStartDate:', currentStartDate);
    console.log('🗓️  Initial date formatted:', currentStartDate.toLocaleDateString());
    
    // Booking type change handler
    window.changeBookingType = function(type) {
        console.log('🔄 Changing booking type to:', type);
        
        bookingMode = type;
        document.getElementById('bookingType').value = type;
        
        const hourlyInterface = document.getElementById('hourlyBookingInterface');
        const dailyInterface = document.getElementById('dailyBookingInterface');
        const description = document.getElementById('bookingTypeDescription');
        
        console.log('📋 Found elements:', {
            hourlyInterface: !!hourlyInterface,
            dailyInterface: !!dailyInterface,
            description: !!description
        });
        
        if (type === 'hourly') {
            console.log('⏰ Switching to hourly mode');
            if (hourlyInterface) hourlyInterface.style.display = 'block';
            if (dailyInterface) dailyInterface.style.display = 'none';
            if (description) description.textContent = 'Select specific time slots for your reservation';
            // Load hourly calendar
            loadCalendarData();
        } else {
            console.log('📅 Switching to daily mode');
            if (hourlyInterface) hourlyInterface.style.display = 'none';
            if (dailyInterface) dailyInterface.style.display = 'block';
            if (description) description.textContent = `Select days for your reservation (maximum ${maxDailyBookingDays} days)`;
            // Load daily calendar
            loadDailyCalendar();
        }
        
        // Reset selections
        clearSelection();
    };
    
    // Daily booking functions
    window.toggleDaySelection = function(dateStr, element) {
        const dayIndex = selectedDays.indexOf(dateStr);
        
        if (dayIndex > -1) {
            // Remove day
            selectedDays.splice(dayIndex, 1);
            element.classList.remove('selected-day');
        } else {
            // Check if we've reached the maximum limit
            if (selectedDays.length >= maxDailyBookingDays) {
                alert(`You can only select up to ${maxDailyBookingDays} days for this property.`);
                return;
            }
            
            // Add day
            selectedDays.push(dateStr);
            element.classList.add('selected-day');
        }
        
        updateSelectedDaysDisplay();
        validateDaySelection();
    };
    
    function updateSelectedDaysDisplay() {
        const summaryElement = document.getElementById('selectedDaysSummary');
        const countElement = document.getElementById('selectedDaysCount');
        const listElement = document.getElementById('selectedDaysList');
        const formInput = document.getElementById('selectedDays');
        
        if (selectedDays.length === 0) {
            summaryElement.style.display = 'none';
            formInput.value = '';
        } else {
            summaryElement.style.display = 'block';
            
            // Sort days chronologically
            selectedDays.sort();
            
            // Update count
            countElement.textContent = selectedDays.length;
            
            // Create day badges
            const dayElements = selectedDays.map(dateStr => {
                // Parse date as local date to avoid timezone issues
                const dateParts = dateStr.split('-');
                const date = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));
                const dayName = date.toLocaleDateString('en-US', { weekday: 'short' });
                const dateDisplay = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                return `<span class="badge bg-primary me-1 mb-1">${dayName} ${dateDisplay}</span>`;
            });
            
            listElement.innerHTML = dayElements.join('');
            formInput.value = JSON.stringify(selectedDays);
        }
        
        // Update form display
        updateSelectedTimeDisplay();
        
        // Validate day selection
        validateDaySelection();
    }
    
    function validateDaySelection() {
        const submitBtn = document.getElementById('submitReservation');
        const warningElement = document.getElementById('maxDaysWarning');
        const reservationForm = document.getElementById('quickReservationForm');
        
        if (bookingMode === 'daily') {
            if (selectedDays.length === 0) {
                submitBtn.disabled = true;
                warningElement.style.display = 'none';
                if (reservationForm) {
                    reservationForm.style.display = 'none';
                }
            } else if (selectedDays.length > maxDailyBookingDays) {
                submitBtn.disabled = true;
                warningElement.style.display = 'block';
                if (reservationForm) {
                    reservationForm.style.display = 'none';
                }
            } else {
                submitBtn.disabled = false;
                warningElement.style.display = 'none';
                // Show the reservation form when valid number of days are selected
                showReservationForm();
            }
        } else {
            submitBtn.disabled = false;
            warningElement.style.display = 'none';
        }
    }
    
    // Daily calendar loading function
    function loadDailyCalendar() {
        console.log('📅 Loading daily calendar...');
        const container = document.getElementById('dailyCalendarGrid');
        
        if (!container) {
            console.error('❌ dailyCalendarGrid container not found!');
            return;
        }
        
        console.log('✅ Daily calendar container found, loading...');
        
        container.innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-2 small text-muted">Loading monthly calendar...</div>
            </div>
        `;
        
        // Generate a full month view starting from current week
        const startOfMonth = new Date(currentStartDate.getFullYear(), currentStartDate.getMonth(), 1);
        const endOfMonth = new Date(currentStartDate.getFullYear(), currentStartDate.getMonth() + 1, 0);
        
        // Find the first Sunday of the calendar view (may be in previous month)
        const firstSunday = new Date(startOfMonth);
        firstSunday.setDate(firstSunday.getDate() - firstSunday.getDay());
        
        // Generate all dates for the calendar (6 weeks = 42 days)
        const dates = [];
        for (let i = 0; i < 42; i++) {
            const date = new Date(firstSunday);
            date.setDate(date.getDate() + i);
            dates.push(date.toISOString().split('T')[0]);
        }
        
        console.log('🗓️  Loading full calendar for dates:', dates);
        
        Promise.all(dates.map(date => 
            fetch(`/api/properties/${propertyId}/calendar?date=${date}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Check for any bookings during business hours (8 AM to 6 PM)
                    const businessHours = data.slots.filter(slot => {
                        const hour = parseInt(slot.time.split(':')[0]);
                        return hour >= 8 && hour <= 17;
                    });
                    
                    const bookedSlots = businessHours.filter(slot => !slot.available);
                    const hasBookings = bookedSlots.length > 0;
                    const isFullyBooked = bookedSlots.length === businessHours.length;
                    
                    return {
                        date: data.date,
                        hasBookings: hasBookings,
                        isFullyBooked: isFullyBooked,
                        bookedSlots: bookedSlots.length,
                        totalSlots: businessHours.length,
                        property: data.property,
                        slots: data.slots
                    };
                })
                .catch(dateError => {
                    console.error(`Error loading daily data for ${date}:`, dateError);
                    return {
                        date: date,
                        hasBookings: false,
                        isFullyBooked: false,
                        bookedSlots: 0,
                        totalSlots: 10,
                        error: dateError.message
                    };
                })
        ))
        .then(dataArray => {
            console.log('Full calendar data received:', dataArray);
            renderDailyCalendarGrid(dataArray, startOfMonth, endOfMonth);
        })
        .catch(error => {
            console.error('Error loading daily calendar data:', error);
            container.innerHTML = `
                <div class="text-center py-3 text-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <div class="mt-2 small">Failed to load daily calendar</div>
                    <div class="mt-1 small text-muted">${error.message}</div>
                </div>
            `;
        });
    }
       
    function renderDailyCalendarGrid(dataArray, startOfMonth, endOfMonth) {
        const container = document.getElementById('dailyCalendarGrid');
        
        if (!dataArray || dataArray.length === 0) {
            container.innerHTML = `
                <div class="text-center py-3 text-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <div class="mt-2">No daily calendar data available</div>
                </div>
            `;
            return;
        }
        
        // Create full calendar view
        let calendarHTML = '<div class="full-calendar-container">';
        
        // Add month header
        const monthName = startOfMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        calendarHTML += `
            <div class="calendar-month-header">
                <h6 class="mb-0">${monthName}</h6>
                <small class="text-muted">Select days for booking (maximum ${maxDailyBookingDays} days)</small>
            </div>
        `;
        
        // Add weekday headers
        calendarHTML += '<div class="calendar-weekdays">';
        const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        weekdays.forEach(day => {
            calendarHTML += `<div class="weekday-header">${day}</div>`;
        });
        calendarHTML += '</div>';
        
        // Add calendar grid
        calendarHTML += '<div class="calendar-days-grid">';
        
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        dataArray.forEach((dayData, index) => {
            if (!dayData || !dayData.date) return;
            
            const dateParts = dayData.date.split('-');
            const date = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));
            date.setHours(0, 0, 0, 0);
            
            const isPast = date.getTime() < today.getTime();
            const isCurrentMonth = date.getMonth() === startOfMonth.getMonth();
            const isToday = date.getTime() === today.getTime();
            const dayNumber = date.getDate();
            const isSelected = selectedDays.includes(dayData.date);
            
            // Determine availability and booking status
            let status = 'available';
            let statusText = '';
            let cellClass = 'calendar-day-cell';
            
            if (isPast) {
                status = 'past';
                statusText = 'Past';
                cellClass += ' past-day';
            } else if (dayData.hasBookings) {
                if (dayData.isFullyBooked) {
                    status = 'fully-booked';
                    statusText = 'Booked';
                    cellClass += ' fully-booked-day';
                } else {
                    status = 'partially-booked';
                    statusText = 'See hourly schedule for details';
                    cellClass += ' partially-booked-day';
                }
            } else {
                status = 'available';
                cellClass += ' available-day';
            }
            
            if (isSelected) {
                cellClass += ' selected-day';
            }
            
            if (!isCurrentMonth) {
                cellClass += ' other-month';
            }
            
            if (isToday) {
                cellClass += ' today';
            }
            
            // Only allow clicking on fully available days in current/future months
            const canSelect = !isPast && !dayData.hasBookings && isCurrentMonth;
            const clickHandler = canSelect ? `onclick="toggleDaySelection('${dayData.date}', this)"` : '';
            
            calendarHTML += `
                <div class="${cellClass}" ${clickHandler} data-date="${dayData.date}" title="${dayData.date}">
                    <div class="day-number">${dayNumber}</div>
                    ${statusText ? `<div class="day-status" align="center">${statusText}</div>` : ''}
                 
                </div>
            `;
        });
        
        calendarHTML += '</div></div>';
        container.innerHTML = calendarHTML;
    }
    
    // Initialize calendar
    updateDateDisplay();
    loadCalendarData();
    
    // Add event listeners for booking type radio buttons
    document.getElementById('hourlyBooking').addEventListener('change', function() {
        if (this.checked) {
            changeBookingType('hourly');
        }
    });
    
    document.getElementById('dailyBooking').addEventListener('change', function() {
        if (this.checked) {
            changeBookingType('daily');
        }
    });
    
    // Date navigation
    document.getElementById('prevDay').addEventListener('click', function() {
        if (bookingMode === 'daily') {
            // Move to previous month
            currentStartDate.setMonth(currentStartDate.getMonth() - 1);
        } else {
            // Move to previous week
            currentStartDate.setDate(currentStartDate.getDate() - 7);
        }
        updateDateDisplay();
        
        if (bookingMode === 'daily') {
            loadDailyCalendar();
        } else {
            loadCalendarData();
        }
        clearSelection();
    });
    
    document.getElementById('nextDay').addEventListener('click', function() {
        if (bookingMode === 'daily') {
            // Move to next month
            currentStartDate.setMonth(currentStartDate.getMonth() + 1);
        } else {
            // Move to next week
            currentStartDate.setDate(currentStartDate.getDate() + 7);
        }
        updateDateDisplay();
        
        if (bookingMode === 'daily') {
            loadDailyCalendar();
        } else {
            loadCalendarData();
        }
        clearSelection();
    });
    
    // Quick date buttons
    document.querySelectorAll('[data-date-offset]').forEach(button => {
        button.addEventListener('click', function() {
            const offset = parseInt(this.dataset.dateOffset);
            const targetDate = new Date();
            targetDate.setDate(targetDate.getDate() + offset);
            currentStartDate = getStartOfWeek(targetDate);
            updateDateDisplay();
            
            if (bookingMode === 'daily') {
                loadDailyCalendar();
            } else {
                loadCalendarData();
            }
            clearSelection();
        });
    });
    
    // Cancel selection
    document.getElementById('cancelSelection').addEventListener('click', clearSelection);
    
    // Form submission debugging
    document.getElementById('quickReservationForm').addEventListener('submit', function(e) {
        console.log('Form submission detected');
        console.log('Start time:', document.getElementById('selectedStartTime').value);
        console.log('End time:', document.getElementById('selectedEndTime').value);
        
        // Let the form submit naturally
        console.log('Allowing form to submit...');
    });
    
    function updateDateDisplay() {
        if (bookingMode === 'daily') {
            // Show month for daily view
            const monthFormatted = currentStartDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            document.getElementById('dateRange').textContent = monthFormatted;
            
            // Update navigation button text
            document.getElementById('prevDay').innerHTML = '<i class="bi bi-chevron-left"></i> Previous Month';
            document.getElementById('nextDay').innerHTML = 'Next Month <i class="bi bi-chevron-right"></i>';
        } else {
            // Show week range for hourly view
            const endDate = new Date(currentStartDate);
            endDate.setDate(endDate.getDate() + 6);
            
            const startFormatted = currentStartDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            const endFormatted = endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            
            document.getElementById('dateRange').textContent = `${startFormatted} - ${endFormatted}`;
            
            // Update navigation button text
            document.getElementById('prevDay').innerHTML = '<i class="bi bi-chevron-left"></i> Previous Week';
            document.getElementById('nextDay').innerHTML = 'Next Week <i class="bi bi-chevron-right"></i>';
        }
    }
    
    function loadCalendarData() {
        const container = document.getElementById('calendarGrid');
        
        container.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-2 small text-muted">Loading 7-day calendar...</div>
            </div>
        `;
        
        const dates = [];
        for (let i = 0; i < 7; i++) {
            const date = new Date(currentStartDate);
            date.setDate(date.getDate() + i);
            dates.push(date.toISOString().split('T')[0]);
        }
        
        console.log('🗓️  Loading calendar for dates:', dates);
        console.log('🗓️  Current start date:', currentStartDate);
        console.log('🗓️  Today:', new Date());
        
        Promise.all(dates.map(date => 
            fetch(`/api/properties/${propertyId}/calendar?date=${date}`)
                .then(response => {
                    console.log(`📅 API response for ${date}:`, response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        return response.text().then(text => {
                            console.error(`Non-JSON response for ${date}:`, text);
                            throw new Error('Expected JSON response but received HTML/text');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(`📊 Data for ${date}:`, data);
                    const unavailableSlots = data.slots ? data.slots.filter(slot => !slot.available) : [];
                    if (unavailableSlots.length > 0) {
                        console.log(`🚫 ${unavailableSlots.length} unavailable slots on ${date}:`, unavailableSlots);
                    }
                    return data;
                })
                .catch(dateError => {
                    console.error(`Error loading data for ${date}:`, dateError);
                    // Return a fallback object for this date
                    return {
                        date: date,
                        slots: [],
                        error: dateError.message,
                        property: { id: propertyId, name: 'Unknown', price_per_hour: 0 }
                    };
                })
        ))
        .then(dataArray => {
            console.log('7-day calendar data received:', dataArray);
            renderCalendarGrid(dataArray);
        })
        .catch(error => {
            console.error('Error loading calendar data:', error);
            container.innerHTML = `
                <div class="text-center py-4 text-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <div class="mt-2 small">Failed to load calendar</div>
                    <div class="mt-1 small text-muted">${error.message}</div>
                </div>
            `;
        });
    }
    
    function renderCalendarGrid(dataArray) {
        const container = document.getElementById('calendarGrid');
        
        // Check if we have valid data
        if (!dataArray || dataArray.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4 text-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <div class="mt-2">No calendar data available</div>
                </div>
            `;
            return;
        }
        
        // Find a day with valid slots data for the time structure
        const validDay = dataArray.find(day => day && day.slots && day.slots.length > 0);
        if (!validDay) {
            container.innerHTML = `
                <div class="text-center py-4 text-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <div class="mt-2">No valid calendar data available</div>
                    <div class="mt-1 small text-muted">All days returned errors</div>
                </div>
            `;
            return;
        }
        
        // Create calendar structure
        let calendarHTML = `
            <div class="calendar-header">
                <div class="calendar-time-header">Time</div>
        `;
        
        // Add date headers
        dataArray.forEach((dayData, index) => {
            if (!dayData || !dayData.date) {
                calendarHTML += `
                    <div class="calendar-date-header">
                        <span class="date">Error</span>
                        <span class="day">-</span>
                    </div>
                `;
                return;
            }
            
            // Parse date explicitly as local date to avoid timezone issues
            const dateParts = dayData.date.split('-');
            const date = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Reset time for accurate comparison
            date.setHours(0, 0, 0, 0); // Reset time for accurate comparison
            
            const isToday = date.getTime() === today.getTime();
            const isPast = date.getTime() < today.getTime();
            const dayName = date.toLocaleDateString('en-US', { weekday: 'short' });
            const dateNum = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            
            console.log(`📅 Day ${index}: API date="${dayData.date}"`);
            console.log(`📅 Parsed date: ${date.toISOString()} (${date.toLocaleDateString()})`);
            console.log(`📅 Today: ${today.toISOString()} (${today.toLocaleDateString()})`);
            console.log(`📅 isToday=${isToday}, isPast=${isPast}`);
            console.log(`📅 Display: ${dateNum} ${dayName}`);
            
            calendarHTML += `
                <div class="calendar-date-header ${isPast ? 'past-date' : ''} ${isToday ? 'today-date' : ''}">
                    <span class="date">${dateNum}</span>
                    <span class="day">${dayName}</span>
                    ${isPast ? '<small class="text-muted">(Past)</small>' : ''}
                    ${isToday ? '<small class="text-primary">(Today)</small>' : ''}
                    ${dayData.error ? '<small class="text-danger">(Error)</small>' : ''}
                </div>
            `;
        });
        
        calendarHTML += '</div><div class="calendar-body">';
        
        // Generate time slots using the valid day's time structure
        const timeSlots = validDay.slots;
        
        timeSlots.forEach((slot, timeIndex) => {
            calendarHTML += `
                <div class="calendar-row">
                    <div class="calendar-time-cell">${slot.display_time}</div>
            `;
            
            // Add slots for each day
            dataArray.forEach((dayData, dayIndex) => {
                const daySlot = dayData.slots[timeIndex];
                const slotDate = new Date(daySlot.datetime);
                const now = new Date();
                
                // More precise past check - add 5 minute buffer
                const bufferTime = 5 * 60 * 1000; // 5 minutes in milliseconds
                const isPastSlot = (slotDate.getTime() + bufferTime) < now.getTime();
                const isAvailableAndNotPast = daySlot.available && !isPastSlot;
                
                // Debug unavailable slots
                if (!daySlot.available) {
                    console.log(`🚫 UNAVAILABLE SLOT: Day ${dayIndex} (${dayData.date}), Time ${daySlot.display_time}, DateTime: ${daySlot.datetime}`);
                    if (daySlot.conflict) {
                        console.log(`   Conflict: ${daySlot.conflict.type} - ${daySlot.conflict.contact} (${daySlot.conflict.start} to ${daySlot.conflict.end})`);
                    }
                }
                
                console.log(`Slot Day ${dayIndex} ${daySlot.datetime}: slotTime=${slotDate.getTime()}, nowTime=${now.getTime()}, isPast=${isPastSlot}, available=${daySlot.available}`);
                
                let slotClass = 'booked'; // Default to booked
                if (isPastSlot) {
                    slotClass = 'past';
                } else if (daySlot.available) {
                    slotClass = 'available';
                }
                
                const slotId = `slot_${dayIndex}_${timeIndex}`;
                
                calendarHTML += `
                    <div class="calendar-slot ${slotClass}" 
                         data-day="${dayIndex}" 
                         data-time="${timeIndex}" 
                         data-datetime="${daySlot.datetime}"
                         data-available="${isAvailableAndNotPast}"
                         data-past="${isPastSlot}"
                         id="${slotId}">
                        <div class="slot-content">
                `;
                
                if (isPastSlot) {
                    calendarHTML += '<span class="slot-past">Past</span>';
                } else if (daySlot.available) {
                    calendarHTML += '<span class="slot-available">✓</span>';
                } else {
                    calendarHTML += '<span class="slot-booked">✗</span>';
                    if (daySlot.conflict) {
                        calendarHTML += `
                            <div class="conflict-info">
                                ${daySlot.conflict.contact}
                            </div>
                        `;
                    }
                }
                
                calendarHTML += '</div></div>';
            });
            
            calendarHTML += '</div>';
        });
        
        calendarHTML += '</div>';
        container.innerHTML = calendarHTML;
        
        console.log('🎯 Setting up SINGLE delegated click handler');
        
        // Remove any existing event listeners
        const newContainer = container.cloneNode(true);
        container.parentNode.replaceChild(newContainer, container);
        const cleanContainer = document.getElementById('calendarGrid');
        
        // Use ONLY event delegation - no individual slot listeners
        cleanContainer.addEventListener('click', function(e) {
            const slot = e.target.closest('.calendar-slot');
            console.log('=== SINGLE DELEGATED CLICK HANDLER ===');
            console.log('Clicked element:', e.target);
            console.log('Closest slot:', slot);
            
            if (slot) {
                const isAvailable = slot.classList.contains('available');
                const isPast = slot.classList.contains('past');
                const isBooked = slot.classList.contains('booked');
                const dataAvailable = slot.dataset.available === 'true';
                const dataPast = slot.dataset.past === 'true';
                
                console.log('Slot classes:', {
                    available: isAvailable,
                    past: isPast,
                    booked: isBooked
                });
                console.log('Slot data attributes:', {
                    dataAvailable: dataAvailable,
                    dataPast: dataPast
                });
                
                if (isPast || dataPast) {
                    console.log('❌ BLOCKED: Past slot clicked - preventing action');
                    alert('Cannot select past time slots');
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
                
                if (!isAvailable || !dataAvailable) {
                    console.log('❌ BLOCKED: Unavailable slot clicked');
                    alert('This time slot is not available');
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
                
                console.log('✅ ALLOWED: Valid slot clicked, calling handleSlotClick');
                console.log('========================');
                handleSlotClick(slot);
            } else {
                console.log('No slot found for click');
            }
        });
    }
    
    function handleSlotClick(slotElement) {
        const dayIndex = parseInt(slotElement.dataset.day);
        const timeIndex = parseInt(slotElement.dataset.time);
        const datetime = slotElement.dataset.datetime;
        const isAvailable = slotElement.dataset.available === 'true';
        const isPast = slotElement.dataset.past === 'true';
        
        if (!isAvailable || isPast) {
            if (isPast) {
                console.log('Cannot select past time slot');
            }
            return;
        }
        
        console.log('Slot clicked:', { dayIndex, timeIndex, datetime });
        
        if (!isSelecting) {
            // Start new selection
            console.log('Starting new selection');
            selectedStartSlot = { element: slotElement, dayIndex, timeIndex, datetime };
            selectedEndSlot = null;
            isSelecting = true;
            
            // Clear previous selections
            document.querySelectorAll('.calendar-slot').forEach(el => {
                el.classList.remove('selected', 'selecting');
            });
            
            slotElement.classList.add('selected');
            console.log('Start slot selected:', selectedStartSlot);
            updateSelectionDisplay();
            
        } else {
            // Complete selection (only allow same day for now)
            console.log('Completing selection. Current day:', dayIndex, 'Start day:', selectedStartSlot.dayIndex);
            if (dayIndex === selectedStartSlot.dayIndex && timeIndex >= selectedStartSlot.timeIndex) {
                console.log('Valid end selection');
                selectedEndSlot = { element: slotElement, dayIndex, timeIndex, datetime };
                
                // Clear previous selecting states
                document.querySelectorAll('.calendar-slot').forEach(el => {
                    el.classList.remove('selecting');
                });
                
                // Highlight range
                console.log('Highlighting range from', selectedStartSlot.timeIndex, 'to', selectedEndSlot.timeIndex);
                let validRange = true;
                let slotsToHighlight = [];
                
                for (let i = selectedStartSlot.timeIndex; i <= selectedEndSlot.timeIndex; i++) {
                    const rangeSlot = document.getElementById(`slot_${dayIndex}_${i}`);
                    console.log('Checking slot:', `slot_${dayIndex}_${i}`, rangeSlot);
                    if (rangeSlot && 
                        rangeSlot.dataset.available === 'true' && 
                        rangeSlot.dataset.past !== 'true') {
                        slotsToHighlight.push(rangeSlot);
                    } else {
                        validRange = false;
                        console.log('Invalid slot found:', `slot_${dayIndex}_${i}`, 
                                   'available:', rangeSlot?.dataset.available, 
                                   'past:', rangeSlot?.dataset.past);
                        break;
                    }
                }
                
                if (validRange && slotsToHighlight.length > 0) {
                    slotsToHighlight.forEach(slot => slot.classList.add('selecting'));
                    isSelecting = false;
                    updateSelectionDisplay();
                    console.log('✅ Valid range selected, calling showReservationForm()...');
                    console.log('Selected start slot:', selectedStartSlot);
                    console.log('Selected end slot:', selectedEndSlot);
                    
                    // Add a small delay to ensure DOM is ready
                    setTimeout(() => {
                        showReservationForm();
                        console.log('showReservationForm() called after delay');
                    }, 100);
                } else {
                    console.log('❌ Invalid range - contains unavailable slots or no slots found');
                    clearSelection();
                    alert('Selected range contains unavailable slots. Please select a different range.');
                }
                
            } else {
                // Invalid selection, restart
                clearSelection();
                handleSlotClick(slotElement);
            }
        }
    }
    
    function updateSelectionDisplay() {
        const display = document.getElementById('selectedTimeDisplay');
        
        if (bookingMode === 'daily') {
            updateSelectedTimeDisplay();
            return;
        }
        
        if (!selectedStartSlot) {
            display.textContent = 'No time selected';
            return;
        }
        
        if (!selectedEndSlot) {
            const startDate = new Date(selectedStartSlot.datetime);
            const timeStr = startDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
            const dateStr = startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            display.innerHTML = `<strong>${dateStr} at ${timeStr}</strong> - Click end time`;
            return;
        }
        
        const startDate = new Date(selectedStartSlot.datetime);
        const endDate = new Date(selectedEndSlot.datetime);
        endDate.setHours(endDate.getHours() + 1);
        
        const startTimeStr = startDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
        const endTimeStr = endDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
        const dateStr = startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        
        display.innerHTML = `<strong>${dateStr}: ${startTimeStr} - ${endTimeStr}</strong>`;
        
        // Update hidden form fields
        document.getElementById('selectedStartTime').value = selectedStartSlot.datetime;
        document.getElementById('selectedEndTime').value = endDate.toISOString().slice(0, 16);
        
        console.log('Updated form fields:');
        console.log('Start time value:', selectedStartSlot.datetime);
        console.log('End time value:', endDate.toISOString().slice(0, 16));
    }
    
    function updateSelectedTimeDisplay() {
        const display = document.getElementById('selectedTimeDisplay');
        
        if (bookingMode === 'daily') {
            if (selectedDays.length === 0) {
                display.textContent = 'No days selected';
            } else {
                const daysText = selectedDays.length === 1 ? '1 day' : `${selectedDays.length} days`;
                display.innerHTML = `<strong>${daysText} selected</strong>`;
            }
        } else {
            // This will be handled by updateSelectionDisplay for hourly mode
        }
    }
    
    function showReservationForm() {
        console.log('=== SHOW RESERVATION FORM DEBUG ===');
        
        // Validate selection based on booking mode
        let hasValidSelection = false;
        
        if (bookingMode === 'hourly') {
            hasValidSelection = selectedStartSlot && selectedEndSlot;
        } else if (bookingMode === 'daily') {
            hasValidSelection = selectedDays.length > 0 && selectedDays.length <= maxDailyBookingDays;
        }
        
        if (!hasValidSelection) {
            console.log('Invalid selection for booking mode:', bookingMode);
            return;
        }
        
        const form = document.getElementById('quickReservationForm');
        console.log('Form element found:', form);
        
        if (form) {
            console.log('Current form display style:', form.style.display);
            form.style.display = 'block';
            console.log('Form display set to block');
            console.log('Form is now visible:', form.offsetHeight > 0);
            
            // Scroll to form
            form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            console.log('Scrolled to form');
        } else {
            console.error('❌ Form element not found! Looking for: quickReservationForm');
            // Try to find any forms on the page
            const allForms = document.querySelectorAll('form');
            console.log('All forms found on page:', allForms);
        }
        console.log('===================================');
    }
    
    function clearSelection() {
        // Clear hourly selections
        selectedStartSlot = null;
        selectedEndSlot = null;
        isSelecting = false;
        
        // Clear daily selections
        selectedDays = [];
        
        document.querySelectorAll('.calendar-slot').forEach(el => {
            el.classList.remove('selected', 'selecting');
        });
        
        document.querySelectorAll('.daily-calendar-cell').forEach(el => {
            el.classList.remove('selected-day');
        });
        
        updateSelectionDisplay();
        updateSelectedDaysDisplay();
        document.getElementById('quickReservationForm').style.display = 'none';
    }
});
</script>
@endsection

