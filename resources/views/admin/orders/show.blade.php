@extends('adminlte::page')

@section('title', 'Order Details')

@section('content')
    <div class="admin-section">
        <div class="section-header">
            <h2>Order #{{ $order->id }}</h2>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Orders List
            </a>
        </div>

        <div class="section-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h4>Customer Information</h4>
                    <p><strong>Name:</strong> {{ $order->user->name ?? 'Guest' }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <h4>Order Details</h4>
                    <p><strong>Status:</strong> <span
                            class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">{{ ucfirst($order->status) }}</span>
                    </p>
                    <p class="mb-1"><small class="text-muted">Last changed: {{ $order->updated_at->format('M d, Y') }}</small></p>
                    <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y ') }}</p>
                    <p><strong>Total:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th>${{ number_format($order->total_amount, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
@endsection
