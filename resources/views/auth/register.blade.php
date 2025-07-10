@extends('layouts.app')

@section('title', 'Create Account')
@section('body-class', 'luxury-login')
@section('main-class', 'login-main')

@push('style')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
<div class="login-wrapper">
    <div class="login-grid">

        <!-- Left: Form -->
        <div class="login-left">
            <h2 class="login-title">Create an Account</h2>
            <p class="login-subtitle">
                Save time at checkout, view your shopping bag and saved items from any device, and access your order history.
            </p>

            <form method="POST" action="{{ route('register') }}" class="login-form">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-input" required>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
                </div>

                <!-- Submit -->
                <button type="submit" class="submit-button">Create Account</button>
            </form>
        </div>

        <!-- Divider -->
        <div class="login-divider"></div>

        <!-- Right: Link to Login -->
        <div class="login-right">
             <div class="login-image">
            <img src="{{ asset('images/register.jpg') }}" alt="Login Banner" class="login-side-image">
        </div>
            <h2 class="login-title">Already a Member?</h2>
            <p class="login-subtitle">
                Sign in to your account to access saved items, orders, and faster checkout.
            </p>

            <a href="{{ route('login') }}" class="submit-button create-button">Sign In</a>
        </div>
    </div>
</div>
@endsection
