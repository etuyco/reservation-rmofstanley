@extends('layouts.app')

@section('title', 'Login - RM of Stanley')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="col-md-6 col-lg-5">
        <div class="card">
            <div class="card-header text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-box-arrow-in-right" style="font-size: 3rem; background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                </div>
                <h2 class="mb-0 fw-bold">Welcome Back</h2>
                <p class="text-muted mb-0 mt-2">Sign in to your account</p>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">
                            <i class="bi bi-envelope me-2"></i>{{ __('Email Address') }}
                        </label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
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
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="text-decoration-none" href="{{ route('password.request') }}" style="color: #3b82f6;">
                                {{ __('Forgot Password?') }}
                            </a>
                        @endif
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('Login') }}
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="text-muted mb-0">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color: #3b82f6;">
                                {{ __('Register') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
