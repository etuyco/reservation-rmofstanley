@extends('layouts.app')

@section('title', 'Register - RM of Stanley')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="col-md-6 col-lg-5">
        <div class="card">
            <div class="card-header text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-person-plus" style="font-size: 3rem; background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                </div>
                <h2 class="mb-0 fw-bold">Create Account</h2>
                <p class="text-muted mb-0 mt-2">Join RM of Stanley today</p>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">
                            <i class="bi bi-person me-2"></i>{{ __('Full Name') }}
                        </label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter your full name">
                        @error('name')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">
                            <i class="bi bi-envelope me-2"></i>{{ __('Email Address') }}
                        </label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter your email">
                        @error('email')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">
                            <i class="bi bi-lock me-2"></i>{{ __('Password') }}
                        </label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Create a password">
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                        <small class="form-text text-muted">Use at least 8 characters with a mix of letters and numbers</small>
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="form-label fw-semibold">
                            <i class="bi bi-lock-fill me-2"></i>{{ __('Confirm Password') }}
                        </label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password">
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-person-plus me-2"></i>{{ __('Create Account') }}
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="text-muted mb-0">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color: #3b82f6;">
                                {{ __('Login') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
