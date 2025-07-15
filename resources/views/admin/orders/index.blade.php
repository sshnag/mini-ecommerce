@extends('adminlte::page')

@section('title', 'Orders')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/admin-style.css') }}">

<div class="admin-section">
    <h2 class="section-header">Orders</h2>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>Order ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
