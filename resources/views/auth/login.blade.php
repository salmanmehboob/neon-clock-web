@extends('layouts.guest')

@section('content')
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light py-5">
        <div class="col-12 col-md-10 col-lg-8 col-xl-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h3 text-center mb-1">{{ __('Welcome back') }}</h1>
                    <p class="text-center text-muted mb-4">{{ __('Sign in to continue') }}</p>

                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

                        <div class="form-floating mb-3">
                            <input
                                id="email"
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="name@example.com"
                                required
                                autocomplete="email"
                                autofocus
                            >
                            <label for="email">{{ __('Email Address') }}</label>
                            @error('email')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input
                                id="password"
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                            >
                            <label for="password">{{ __('Password') }}</label>
                            @error('password')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                            </div>

                            @if (Route::has('password.request'))
                                <a class="link-primary text-decoration-none" href="{{ route('password.request') }}">
                                    {{ __('Forgot Password?') }}
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            {{ __('Login') }}
                        </button>
                    </form>
                </div>
            </div>

            <p class="text-center text-muted mt-4 mb-0 small">
                {{ __("Don't have an account?") }}
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="link-primary text-decoration-none">{{ __('Register') }}</a>
                @endif
            </p>
        </div>
    </div>
@endsection
