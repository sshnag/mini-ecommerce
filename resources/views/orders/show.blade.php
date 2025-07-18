@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}">

<div class="container my-5 luxury-section p-4 rounded bg-white">
    <h2 class="text-gold mb-4">Order Details</h2>

    <div class="mb-3">
        <strong class="text-dark">Order Date:</strong>
        <span>{{ $order->created_at->format('F j, Y') }}</span>
    </div>

    <div class="mb-3">
        <strong class="text-dark">Status:</strong>
        <span class="badge bg-success">{{ ucfirst($order->status) }}</span>
    </div>

    <div class="mb-3">
        <strong class="text-dark">Total:</strong>
        <span class="text-gold">${{ number_format($order->total_amount, 2) }}</span>
    </div>

    <div class="mb-4">
        <h4 class="text-gold">Shipping Address</h4>
<p class="mb-4">
                {{ $order->address->street }}<br>
                {{ $order->address->city }}, {{ $order->address->postal_code }}<br>
                {{ $order->address->country }}
            </p>
    </div>

    <h4 class="text-gold mb-3">Items in this Order</h4>
    <div class="row">
        @foreach ($order->orderItems as $item)
        <div class="col-md-4 mb-4">
            <div class="card h-100 rounded-lg">
                <img src="{{ asset('storage/' . $item->product->image) }}" class="card-img-top rounded-top" alt="{{ $item->product->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $item->product->name }}</h5>
                    <p class="card-text">Quantity: <strong>{{ $item->quantity }}</strong></p>
                    <p class="card-text">Price: ${{ number_format($item->price, 2) }}</p>
                </div>
            </div>
        </div>
        @endforeach

    </div>

    <a href="{{ route('orders.history') }}" class="btn btn-outline-dark mt-4">← Back to Order History</a>
</div>
@endsection
