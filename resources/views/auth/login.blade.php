<x-guest-layout>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Brand Header -->
            <div class="brand-header">
                <h1 class="brand-title">CARTIER</h1>
                <p class="brand-tagline">Timeless Elegance</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf

                <!-- Email Field -->
                <div class="form-field">
                    <label for="email" class="input-label">Email Address</label>
                    <input type="email" name="email" id="email"
                           class="form-control"
                           value="{{ old('email') }}"
                           required autofocus
                           placeholder="your@email.com">
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-field">
                    <label for="password" class="input-label">Password</label>
                    <input type="password" name="password" id="password"
                           class="form-control"
                           required
                           placeholder="••••••••">
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="auth-button">
                    Sign In
                </button>
            </form>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/login.css') }}">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    @endpush
</x-guest-layout>
