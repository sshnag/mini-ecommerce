@extends('layouts.app')

@section('title', 'Order Details')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/orders-show.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="container py-5">

  <div class="mb-4">
    <h2>Order Status:
      <span class="badge
        @if($order->status === 'paid') badge-success
        @elseif($order->status === 'pending') badge-warning
        @elseif($order->status === 'failed') badge-danger
        @else badge-secondary @endif">
        {{ ucfirst($order->status) }}
      </span>
    </h2>
    <p>Placed on: {{ $order->created_at->format('M d, Y') }}</p>
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <h5>Shipping Address</h5>
      @if($order->address)
        <p>
          {{ $order->address->street }}, {{ $order->address->city }},<br>
          {{ $order->address->postal_code }}, {{ $order->address->country }}
        </p>
      @else
        <p>No shipping address available.</p>
      @endif
    </div>
  </div>

  @if($order->payment)
  <div class="card mb-4">
    <div class="card-body">
      <h5>Payment Details</h5>
      <p>Method: {{ ucfirst($order->payment->method) }}</p>
      <p>Status: {{ ucfirst($order->payment->status) }}</p>
      @if($order->payment->transaction_id)
        <p>Transaction ID: {{ $order->payment->transaction_id }}</p>
      @endif
    </div>
  </div>
  @endif

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Product</th>
        <th>QTY</th>
        <th>Price</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach($order->orderItems as $item)
      <tr>
        <td>{{ $item->product->name ?? 'Product not found' }}</td>
        <td>{{ $item->quantity }}</td>
        <td>${{ number_format($item->price, 2) }}</td>
        <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="d-flex justify-content-end">
    <h4>Total Amount: <strong>${{ number_format($order->total_amount, 2) }}</strong></h4>
  </div>

</div>
@endsection
