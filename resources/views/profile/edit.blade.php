@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}">

<div class="container my-5">
    <div class="card shadow p-4 luxury-section">
        <h2 class="text-gold mb-4">Edit Profile</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

       <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label text-gold">Name</label>
        <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
    </div>

    <div class="mb-3">
        <label for="profile_image" class="form-label text-gold">Profile Image</label>
        <input type="file" name="profile_image" class="form-control">
        @if ($user->profile_image)
            <img src="{{ asset($user->profile_image) }}" alt="Profile" width="100" class="mt-2">
        @endif
        @error('profile_image') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <button type="submit" class="btn btn-outline-gold">Save Changes</button>
    <a href="{{ route('profile.index') }}" class="btn btn-link">Cancel</a>
</form>

    </div>
</div>
@endsection
