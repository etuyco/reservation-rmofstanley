@extends('layouts.app')

@section('title', 'My Profile - RM of Stanley')

@section('content')
<div class="page-header mb-4">
    <h1><i class="bi bi-person-circle me-2"></i>My Profile</h1>
    <p class="text-muted mb-0">Manage your account information and settings</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Profile Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <strong><i class="bi bi-person me-2"></i>Name:</strong>
                    </div>
                    <div class="col-sm-8">
                        {{ $user->name }}
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <strong><i class="bi bi-envelope me-2"></i>Email:</strong>
                    </div>
                    <div class="col-sm-8">
                        {{ $user->email }}
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <strong><i class="bi bi-shield-check me-2"></i>Role:</strong>
                    </div>
                    <div class="col-sm-8">
                        <span class="badge" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white;">
                            {{ ucfirst($user->role ?? 'User') }}
                        </span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4">
                        <strong><i class="bi bi-calendar me-2"></i>Member Since:</strong>
                    </div>
                    <div class="col-sm-8">
                        {{ $user->created_at->format('F d, Y') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-key me-2"></i>Password</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Update your password to keep your account secure.</p>
                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-semibold">Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                        <small class="form-text text-muted">Use at least 8 characters</small>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning-charge me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Edit Profile
                    </a>
                    <a href="{{ route('reservations.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-calendar-event me-2"></i>My Reservations
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-info">
                            <i class="bi bi-speedometer2 me-2"></i>Admin Dashboard
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Account Statistics</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Total Reservations</span>
                        <strong>{{ $user->reservations->count() }}</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ min(100, ($user->reservations->count() / max(1, $user->reservations->count())) * 100) }}%; background: linear-gradient(135deg, #059669 0%, #10b981 100%);"></div>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Approved Reservations</span>
                        <strong>{{ $user->reservations->where('status', 'approved')->count() }}</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $user->reservations->count() > 0 ? ($user->reservations->where('status', 'approved')->count() / $user->reservations->count()) * 100 : 0 }}%; background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

