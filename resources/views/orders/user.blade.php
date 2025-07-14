@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container py-5">
    <h2>My Orders</h2>

    @if(session('success'))
        <div class="alert alert-success my-3">{{ session('success') }}</div>
    @endif

    @if($orders->isEmpty())
        <p>You have no orders yet.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark">
                            View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
