@extends('layouts.app')

@section('title', 'Create Account')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('content')
    <div class="container login-container d-flex align-items-center justify-content-center">
        <div class="row w-100">
            <div class="col-md-5 login-form pe-md-5 border-end">
                <h2>Create an Account</h2>
                <p class="mb-4 text-muted">Save time at checkout and access your order history.</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label visually-hidden">Full Name</label>
                        <input type="text" name="name" id="name"
                            class="form-control login-input @error('name') is-invalid @enderror" placeholder="Full Name"
                            value="{{ old('name') }}" autofocus>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label visually-hidden">Email</label>
                        <input type="email" name="email" id="email"
                            class="form-control login-input @error('email') is-invalid @enderror" placeholder="Email"
                            value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3 password-wrapper">
                        <input type="password" name="password" id="password"
                            class="form-control login-input @error('password') is-invalid @enderror" placeholder="Password">
                        <i class="fa-regular fa-eye-slash toggle-password" onclick="togglePassword('password', this)"></i>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4 password-wrapper">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control login-input" placeholder="Confirm Password">
                        <i class="fa-regular fa-eye-slash toggle-password"
                            onclick="togglePassword('password_confirmation', this)"></i>
                    </div>

                    <button type="submit" class="btn btn-outline-dark w-100">Create Account</button>
                    <br>
                    <br>
                </form>
            </div>

            <div class="col-md-6 ps-md-5 login-register">
                <h2>Already a Member?</h2>
                <p class="mb-4 text-muted">
                    Sign in to your account to access saved items, orders, and faster checkout.
                </p>
                <a href="{{ route('login') }}" class="btn btn-outline-dark w-100">Sign In</a>
                <br>
                <br>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function togglePassword(fieldId, iconElement) {
                const input = document.getElementById(fieldId);
                if (input.type === 'password') {
                    input.type = 'text';
                    iconElement.classList.remove('fa-eye-slash');
                    iconElement.classList.add('fa-eye');
                } else {
                    input.type = 'password';
                    iconElement.classList.remove('fa-eye');
                    iconElement.classList.add('fa-eye-slash');
                }
            }
        </script>
    @endpush
@endsection
