@extends('layouts.admin-guest')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-10 bg-white border shadow-xl rounded">
        <h2 class="text-2xl text-center font-semibold text-gray-700 mb-6">Admin & Supplier Login</h2>

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm">Email</label>
                <input type="email" name="email" id="email"
                       class="w-full border rounded px-3 py-2"
                       value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm">Password</label>
                <input type="password" name="password" id="password"
                       class="w-full border rounded px-3 py-2" required>
                @error('password')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-between items-center mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-sm">Remember Me</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">
                    Forgot?
                </a>
            </div>

            <button type="submit"
                    class="w-full bg-blue-900 text-white py-2 rounded hover:bg-blue-800">
                Login
            </button>
        </form>
    </div>
</div>
@endsection
