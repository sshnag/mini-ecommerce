@extends('layouts.app')

@section('title', 'Review Order')
@push('style')
    <link rel="stylesheet" href="{{ asset('css/checkout-shipping.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/stock-validation.js') }}"></script>
@endpush

@section('content')
    <div class="container py-5 checkout-review-page">
        <h2>Review Your Order</h2>

        <div class="card mb-4">
            <div class="card-body">
                <h5>Shipping Address</h5>
                <p>{{ $address->street }}, {{ $address->city }}, {{ $address->postal_code }}, {{ $address->country }}</p>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <h5>Payment Method</h5>
                <p>
                    @if (!empty($paymentMethod))
                        @switch($paymentMethod)
                            @case('paypal')
                                PayPal
                            @break

                            @case('card')
                                Credit/Debit Card
                            @break

                            @case('cod')
                                Cash on Delivery
                            @break

                            @default
                                Not specified
                        @endswitch
                    @else
                        Not specified
                    @endif
                </p>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>QTY</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @if ($cartItems && $cartItems->count())
                    @foreach ($cartItems as $item)
                        <tr class="{{ $item->quantity > $item->product->stock ? 'table-warning' : '' }}">
                            <td>
                                {{ $item->product->name ?? 'Product not found' }}
                                @if($item->quantity > $item->product->stock)
                                    <div class="text-danger small">
                                        <i class="fas fa-exclamation-circle"></i>
                                        Only {{ $item->product->stock }} available
                                    </div>
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->product->price ?? 0, 2) }}</td>
                            <td>${{ number_format($item->quantity * ($item->product->price ?? 0), 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">Your cart is empty.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="d-flex justify-content-end align-items-center mb-4">
            <h4>Total: <strong>${{ number_format($total, 2) }}</strong></h4>
        </div>

        <form id="place-order-form" action="{{ route('checkout.place') }}" method="POST">
            @csrf
            <button type="button" id="place-order-btn" class="btn-gold">
                <i class="fas fa-lock me-2"></i>Place Order
            </button>
        </form>

        <div class="mt-3">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Stock availability is checked in real-time. If items become unavailable, you'll be notified.
            </small>
        </div>
    </div>
@endsection
