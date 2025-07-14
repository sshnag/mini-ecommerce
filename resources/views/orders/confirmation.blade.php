@extends('layouts.app')

@section('title', 'Order Confirmation')

@section('content')
<div class="container py-5">
    <h2>Thank you for your order!</h2>
    <p>Your order <strong>#{{ $order->id }}</strong> has been successfully placed.</p>

    <h4>Shipping Address</h4>
    <p>
        {{ $order->address->street }},<br>
        {{ $order->address->city }}, {{ $order->address->postal_code }},<br>
        {{ $order->address->country }}
    </p>

    <h4>Order Details</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end">
        <h4>Total Paid: ${{ number_format($order->total_amount, 2) }}</h4>
    </div>

    <a href="{{ route('home') }}" class="btn btn-primary mt-4">Continue Shopping</a>
</div>
@endsection
