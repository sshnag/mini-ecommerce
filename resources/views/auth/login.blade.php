@extends('layouts.app')

@section('title', 'Sign In')

@push('style')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('content')
<div class="container login-container d-flex align-items-center justify-content-center">
    <div class="row w-100">
        <div class="col-md-5 login-form pe-md-5 border-end">
            <h2>Sign In</h2>
            <p class="mb-4 text-muted">Please sign in to your Tiffany Account.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label visually-hidden">Email</label>
                    <input type="email" name="email" id="email" class="form-control login-input @error('email') is-invalid @enderror"  placeholder="Email" value="{{ old('email') }}" autofocus>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

<div class="mb-4 password-wrapper">
    <input type="password" name="password" id="password"
           class="form-control login-input @error('password') is-invalid @enderror"
           placeholder="Password">
    <i class="fas fa-eye-slash toggle-password" onclick="togglePassword()"></i>
    @error('password')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
                <button type="submit" class="btn btn-outline-dark w-100">Sign In</button>

                <div class="mt-3">
                     @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot Password?</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="col-md-6 ps-md-5 login-register">
            <h2>Create an Account</h2>
            <p class="mb-4 text-muted">
                Save time during checkout, view your shopping bag and saved items from any device and access your order history.
            </p>
            <a href="{{ route('register') }}" class="btn btn-outline-dark w-100">Register</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const icon = document.querySelector('.toggle-password');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    } else {
        passwordInput.type = 'password';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    }
}
</script>
@endpush
@endsection
