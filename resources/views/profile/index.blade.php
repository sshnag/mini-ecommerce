@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}">

<div class="container my-5">
    <h2 class="mb-4 text-gold">My Profile</h2>

    <div class="card shadow p-4 mb-5 luxury-section d-flex flex-row align-items-center">
        <div class="me-4">
            @if ($user->profile_image)
                <img src="{{ Storage::url($user->profile_image) }}"
                     alt="Profile Image"
                     class="rounded-circle"
                     width="120"
                     height="120">
            @else
                <img src="{{ asset('images/default-profile.jpg') }}"
                     alt="Default Image"
                     class="rounded-circle"
                     width="120"
                     height="120">
            @endif
        </div>
        <div>
            <h4 class="text-gold">Account Info</h4>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-gold">Edit Profile</a>
        </div>
    </div>

    <h2 class="mb-4 text-gold">My Orders</h2>
    @if ($orders->count())
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
                                <a href="{{ route('orders.userShow', $order->id) }}"
                                   class="btn btn-outline-gold btn-sm">
                                    View
                                </a>
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
        <div class="alert alert-info">You have no orders yet.</div>
    @endif
</div>
@endsection
