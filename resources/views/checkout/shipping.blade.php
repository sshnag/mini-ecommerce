@extends('layouts.app')

@section('title', 'Shipping Information')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/checkout-shipping.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
    <div class="checkout-container">
        <div class="checkout-card">
            <h2 class="checkout-title">Shipping Details</h2>
            <form action="{{ route('checkout.shipping.store') }}" method="POST" class="checkout-form">
                @csrf

                <div class="form-group">
                    <label for="street">Street</label>
                    <input type="text" id="street" name="street" value="{{ old('street') }}"
                        class="form-input @error('street') is-invalid @enderror" placeholder="123 Main St">
                    @error('street')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="{{ old('city') }}"
                        class="form-input @error('city') is-invalid @enderror" placeholder="New York">
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="postal_code">Postal Code</label>
                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                        class="form-input @error('postal_code') is-invalid @enderror" placeholder="10001">
                    @error('postal_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select id="payment_method" name="payment_method"
                        class="form-input @error('payment_method') is-invalid @enderror">
                        <option value="">Select Payment Method</option>
                        <option value="paypal" @selected(old('payment_method') == 'paypal')>PayPal</option>
                        <option value="card" @selected(old('payment_method') == 'card')>Credit Card</option>
                        <option value="cod" @selected(old('payment_method') == 'cod')>Cash on Delivery</option>
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="country">Country</label>
                    <select id="country" name="country" class="form-input @error('country') is-invalid @enderror">
                        <option value="">Select your country</option>
                        @foreach (config('countries') as $code => $name)
                            <option value="{{ $name }}" @selected(old('country') == $name)>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-gold">Continue to Review</button>
            </form>
        </div>
    </div>
@endsection
