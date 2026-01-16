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
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Availability Calendar</h5>
            </div>
            <div class="card-body">
                <!-- Date Navigation -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-outline-secondary btn-sm" id="prevDay">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div class="text-center">
                        <h6 class="mb-0" id="dateRange"></h6>
                        <small class="text-muted">3-Day View</small>
                    </div>
                    <button class="btn btn-outline-secondary btn-sm" id="nextDay">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                <!-- Quick Date Buttons -->
                <div class="d-flex gap-1 mb-3">
                    <button class="btn btn-outline-primary btn-sm flex-fill" data-date-offset="0">Today</button>
                    <button class="btn btn-outline-primary btn-sm flex-fill" data-date-offset="1">Tomorrow</button>
                    <button class="btn btn-outline-primary btn-sm flex-fill" data-date-offset="7">Next Week</button>
                </div>

                <!-- 3-Day Calendar Grid -->
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

    <div class="col-lg-4">
        <div class="card sticky-top mb-4" style="top: 2rem;">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Property Details</h5>
            </div>
            <div class="card-body">
                @if($property->description)
                    <p class="card-text" style="line-height: 1.8; font-size: 1.05rem;">{{ $property->description }}</p>
                @else
                    <p class="text-muted">No description available.</p>
                @endif
                
                <div class="row g-3 mt-2">
                    @if($property->capacity)
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 rounded" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <div class="me-3">
                                    <i class="bi bi-people-fill" style="font-size: 2rem; color: #3b82f6;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Capacity</small>
                                    <strong style="font-size: 1.25rem;">{{ $property->capacity }} people</strong>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-12">
                        <div class="d-flex align-items-center p-3 rounded" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <div class="me-3">
                                <i class="bi bi-currency-dollar" style="font-size: 2rem; color: #10b981;"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Price per Hour</small>
                                <strong style="font-size: 1.25rem;">
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
        </div>

        <!-- Reservation Form -->
        <div class="card sticky-top" style="top: calc(2rem + 20px);">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning-charge me-2"></i>Quick Reservation</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0 mb-3" style="background: linear-gradient(135deg, #e8f4fd 0%, #bee8ff 100%);">
                    <div class="text-center">
                        <i class="bi bi-info-circle" style="color: #0ea5e9;"></i>
                        <small class="d-block mt-1" style="color: #0369a1;">
                            <strong>Click time slots</strong> to select your reservation time
                        </small>
                    </div>
                </div>

                <form id="quickReservationForm" action="{{ route('reservations.store', $property) }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="start_time" id="selectedStartTime">
                    <input type="hidden" name="end_time" id="selectedEndTime">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Selected Time</label>
                        <div class="p-2 bg-light rounded" id="selectedTimeDisplay">
                            No time selected
                        </div>
                    </div>

                    @guest
                    <div class="row g-2 mb-3">
                        <div class="col-12">
                            <input type="text" class="form-control form-control-sm" name="guest_name" placeholder="Your Name" required>
                        </div>
                        <div class="col-12">
                            <input type="email" class="form-control form-control-sm" name="guest_email" placeholder="Your Email" required>
                        </div>
                        <div class="col-12">
                            <input type="tel" class="form-control form-control-sm" name="guest_phone" placeholder="Your Phone" required>
                        </div>
                    </div>
                    @endguest

                    <div class="mb-3">
                        <textarea class="form-control form-control-sm" name="purpose" placeholder="Purpose of reservation..." rows="2" required></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning" id="submitReservation">
                            <i class="bi bi-calendar-plus me-1"></i> Make Reservation
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="cancelSelection">
                            Cancel Selection
                        </button>
                    </div>
                </form>

                @guest
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        Want to track reservations? 
                        <a href="{{ route('register') }}" class="text-decoration-none">Create account</a>
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
.calendar-grid {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    background: white;
}

.calendar-header {
    display: grid;
    grid-template-columns: 80px repeat(3, 1fr);
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.calendar-time-header {
    padding: 12px 8px;
    border-right: 1px solid #dee2e6;
    font-size: 0.8rem;
    font-weight: 600;
    color: #6c757d;
    text-align: center;
}

.calendar-date-header.past-date {
    background: #f8f9fa;
    opacity: 0.6;
    color: #6c757d;
}

.calendar-date-header.today-date {
    background: #e8f4fd;
    color: #0369a1;
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
    grid-template-columns: 80px repeat(3, 1fr);
    border-bottom: 1px solid #f0f0f0;
    min-height: 50px;
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
    background: #fafafa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.calendar-slot {
    border-right: 1px solid #f0f0f0;
    padding: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 50px;
    position: relative;
}

.calendar-slot:last-child {
    border-right: none;
}

.calendar-slot.available {
    background: #f8fff8;
    border-color: #d4edda;
}

.calendar-slot.available:hover {
    background: #198754;
    color: white;
}

.calendar-slot.booked {
    background: #fff5f5;
    border-color: #f5c6cb;
    cursor: not-allowed;
    opacity: 0.7;
}

.calendar-slot.selected {
    background: #ffc107 !important;
    color: #000;
}

.calendar-slot.selecting {
    background: #fff3cd !important;
    color: #664d03;
}

.slot-content {
    text-align: center;
    font-size: 0.8rem;
    width: 100%;
}

.slot-available {
    color: #198754;
    font-weight: 500;
}

.slot-booked {
    color: #dc3545;
    font-size: 0.7rem;
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
    border-color: #198754;
    color: #198754;
}

.time-slot.available:hover {
    background: #198754;
    color: white;
    border-color: #198754;
}

.time-slot.booked {
    background: #f8d7da;
    border-color: #dc3545;
    color: #721c24;
    cursor: not-allowed;
    opacity: 0.7;
}

.time-slot.selected {
    background: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.time-slot.selecting {
    background: #fff3cd;
    border-color: #ffc107;
    color: #664d03;
}

.date-navigation {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Calendar script loaded');
    const propertyId = {{ $property->id }};
    let currentStartDate = new Date();
    let selectedStartSlot = null;
    let selectedEndSlot = null;
    let isSelecting = false;
    
    console.log('Property ID:', propertyId);
    console.log('🗓️  Initial currentStartDate:', currentStartDate);
    console.log('🗓️  Initial date formatted:', currentStartDate.toLocaleDateString());
    
    // Initialize calendar
    updateDateDisplay();
    loadCalendarData();
    
    // Date navigation
    document.getElementById('prevDay').addEventListener('click', function() {
        currentStartDate.setDate(currentStartDate.getDate() - 1);
        updateDateDisplay();
        loadCalendarData();
        clearSelection();
    });
    
    document.getElementById('nextDay').addEventListener('click', function() {
        currentStartDate.setDate(currentStartDate.getDate() + 1);
        updateDateDisplay();
        loadCalendarData();
        clearSelection();
    });
    
    // Quick date buttons
    document.querySelectorAll('[data-date-offset]').forEach(button => {
        button.addEventListener('click', function() {
            const offset = parseInt(this.dataset.dateOffset);
            currentStartDate = new Date();
            currentStartDate.setDate(currentStartDate.getDate() + offset);
            updateDateDisplay();
            loadCalendarData();
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
        const endDate = new Date(currentStartDate);
        endDate.setDate(endDate.getDate() + 2);
        
        const startFormatted = currentStartDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        const endFormatted = endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        
        document.getElementById('dateRange').textContent = `${startFormatted} - ${endFormatted}`;
    }
    
    function loadCalendarData() {
        const container = document.getElementById('calendarGrid');
        
        container.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-2 small text-muted">Loading 3-day calendar...</div>
            </div>
        `;
        
        const dates = [];
        for (let i = 0; i < 3; i++) {
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
                    return response.json();
                })
                .then(data => {
                    console.log(`📊 Data for ${date}:`, data);
                    const unavailableSlots = data.slots.filter(slot => !slot.available);
                    if (unavailableSlots.length > 0) {
                        console.log(`🚫 ${unavailableSlots.length} unavailable slots on ${date}:`, unavailableSlots);
                    }
                    return data;
                })
        ))
        .then(dataArray => {
            console.log('3-day calendar data received:', dataArray);
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
        
        // Create calendar structure
        let calendarHTML = `
            <div class="calendar-header">
                <div class="calendar-time-header">Time</div>
        `;
        
        // Add date headers
        dataArray.forEach((dayData, index) => {
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
                </div>
            `;
        });
        
        calendarHTML += '</div><div class="calendar-body">';
        
        // Generate time slots (7 AM to 10 PM)
        const timeSlots = dataArray[0].slots; // Use first day's time structure
        
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
    
    function showReservationForm() {
        console.log('=== SHOW RESERVATION FORM DEBUG ===');
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
        selectedStartSlot = null;
        selectedEndSlot = null;
        isSelecting = false;
        
        document.querySelectorAll('.calendar-slot').forEach(el => {
            el.classList.remove('selected', 'selecting');
        });
        
        updateSelectionDisplay();
        document.getElementById('quickReservationForm').style.display = 'none';
    }
});
</script>
@endsection

