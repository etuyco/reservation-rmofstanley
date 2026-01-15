@extends('layouts.app')

@section('title', 'Create Booking - RM of Stanley')

@section('content')
<div class="row mb-4">
    <div class="col">
        <a href="{{ route('properties.show', $property) }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Property
        </a>
        <h1>Book: {{ $property->name }}</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('bookings.store', $property) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time *</label>
                        <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" 
                               id="start_time" name="start_time" 
                               value="{{ old('start_time') }}" required>
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="end_time" class="form-label">End Time *</label>
                        <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" 
                               id="end_time" name="end_time" 
                               value="{{ old('end_time') }}" required>
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="purpose" class="form-label">Purpose</label>
                        <textarea class="form-control @error('purpose') is-invalid @enderror" 
                                  id="purpose" name="purpose" rows="3">{{ old('purpose') }}</textarea>
                        @error('purpose')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Submit Booking Request
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Property Info</h5>
                <p class="card-text">
                    <strong>{{ $property->name }}</strong><br>
                    <span class="badge bg-secondary">{{ $property->type }}</span>
                </p>
                @if($property->price_per_hour)
                    <p class="card-text">
                        <small class="text-muted">${{ number_format($property->price_per_hour, 2) }}/hour</small>
                    </p>
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
                        <span class="status-badge status-in-use">
                            <i class="bi bi-exclamation-triangle"></i> May not be available
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

