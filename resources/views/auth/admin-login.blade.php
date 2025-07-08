@extends('layouts.app')

@section('title', 'Admin Login - Tiffany Jewels')

@section('content')
<div class="login-box">
    <h2 class="font-serif text-3xl mb-4 text-danger">Admin Panel</h2>
    <p class="text-muted mb-4">Authorized access only</p>

    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Admin Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Admin Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-danger w-100">Admin Login</button>
    </form>
</div>
@endsection
