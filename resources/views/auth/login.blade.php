@extends('layouts.app')

@section('title', 'Sign In')
@section('body-class', 'luxury-login')
@section('main-class', 'login-main')

@push('style')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
<div class="login-wrapper">
    <div class="login-grid">

        <div class="login-left">
            <h2 class="login-title">Sign In</h2>
            <p class="login-subtitle">Please sign in to your Tiffany Account.</p>

            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email"
                           class="form-input"
                           value="{{ old('email') }}"  autofocus>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password"
                           class="form-input" >
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit" class="submit-button">Sign In</button>

                <!-- Forgot -->
                @if (Route::has('password.request'))
                <div class="forgot-link">
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                </div>
                @endif
            </form>
        </div>

        <!-- Divider -->
        <div class="login-divider"></div>
        <!-- Register -->
        <div class="login-right">
            <h2 class="login-title">Create an Account</h2>
            <p class="login-subtitle">
                Save time during checkout, view your shopping bag and saved items from any device and access your order history.
            </p>

            <a href="" class="submit-button create-button">
                Register
            </a>
        </div>
    </div>
</div>
@endsection
