@extends('layouts.app')

@section('title', 'Contact Us')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endpush

@section('content')
    <div class="contact-wrapper">
        <div class="contact-form-section">
            <h2 class="contact-title text-gold">Contact Us</h2>

            @if (session('success'))
                <div class="contact-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                @csrf

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}">
                    @error('name')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}">
                    @error('email')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}">
                    @error('subject')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea name="message" id="message" rows="5">{{ old('message') }}</textarea>
                    @error('message')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="submit-btn">Send Message</button>
            </form>

            <div class="faq-section">
                <h3 class="faq-title text-gold">Frequently Asked Questions</h3>

                <div class="faq-item">
                    <input type="checkbox" id="faq1">
                    <label for="faq1" class="faq-question">How long does it take to get a response?</label>
                    <div class="faq-answer">
                        We usually respond within 24–48 business hours.
                    </div>
                </div>

                <div class="faq-item">
                    <input type="checkbox" id="faq2">
                    <label for="faq2" class="faq-question">Can I update my message after sending?</label>
                    <div class="faq-answer">
                        Please send a follow-up using the same email and include “Follow-up” in the subject.
                    </div>
                </div>

                <div class="faq-item">
                    <input type="checkbox" id="faq3">
                    <label for="faq3" class="faq-question">Do you provide customer support on weekends?</label>
                    <div class="faq-answer">
                        Our team operates on Monday to Friday.Weekend queries are answered on Monday.
                    </div>
                </div>


                <!-- More FAQ items as needed -->
            </div>


        </div>

        <div class="contact-image-section">
            <img src="{{ asset('images/register.jpg') }}" alt="Elegant Contact Image">
        </div>
    </div>
@endsection
