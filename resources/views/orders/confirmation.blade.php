@extends('layouts.app')

@section('title', 'Order Confirmation')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/checkout-shipping.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush
@section('content')
<div class="container py-5 text-center">
    <h2 class="display-5 text-gold">Thank you for your order!</h2>
    <p class="lead">Your order <strong>#{{ $order->id }}</strong> has been placed successfully.</p>

    <div class="my-5">
        <h4 class="text-uppercase">Shipping To</h4>
        <p class="mb-4">
            {{ $order->address->street }}<br>
            {{ $order->address->city }}, {{ $order->address->postal_code }}<br>
            {{ $order->address->country }}
        </p>

        <h4 class="text-uppercase">Items Ordered</h4>
        <table class="table table-hover mt-3">
           <thead class="table-light">
    <tr>
        <th>Product</th>
        <th>Category</th>
        <th>Qty</th>
        <th>Unit Price</th>
        <th>Subtotal</th>
    </tr>
</thead>
<tbody>
    @foreach($order->orderItems as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->product->category->name ?? 'N/A' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>${{ number_format($item->price, 2) }}</td>
            <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
        </tr>
    @endforeach
</tbody>

        </table>

        <h4 class="mt-4">Total Amount: <strong>${{ number_format($order->total_amount, 2) }}</strong></h4>
    </div>

    <a href="{{ route('home') }}" class="btn btn-gold">Back To Home</a>
</div>

@endsection
