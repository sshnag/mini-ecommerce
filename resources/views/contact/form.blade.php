@extends('layouts.app')

@section('title', 'Contact Us')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endpush

@section('content')
<div class="contact-container">
    <h2 class="contact-title">Contact Us</h2>

    @if(session('success'))
        <div class="contact-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
        @csrf

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
            @error('name') <small class="error">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}">
            @error('email') <small class="error">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" name="subject" id="subject" value="{{ old('subject') }}">
            @error('subject') <small class="error">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="message">Message</label>
            <textarea name="message" id="message" rows="5">{{ old('message') }}</textarea>
            @error('message') <small class="error">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="submit-btn">Send Message</button>
    </form>
</div>
@endsection
h
