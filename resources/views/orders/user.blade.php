@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}">

<div class="container my-5">
    <h2 class="mb-4 text-gold">My Orders</h2>

    @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover shadow rounded luxury-table">
                <thead class="table-gold text-white">
                    <tr>
                        <th>No.</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
            <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'delivered' ? 'success' : 'secondary') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('F j, Y') }}</td>
                            <td>${{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <a href="{{ route('orders.userShow', $order->id) }}" class="btn btn-outline-gold btn-sm">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links('vendor.pagination.bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info">You have not placed any orders yet.  <a href="{{ route('home') }}" class="btn btn-outline-gold ml-2"> Start Shopping</a></div>
    @endif
</div>
@endsection
