@extends('adminlte::page')

@section('title', 'Order Management')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Order Management</h1>
        <div class="btn-group">
            <button class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-filter"></i> Filter Status
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.orders.index') }}">All Orders</a>
                <div class="dropdown-divider"></div>
                @foreach(['pending', 'paid', 'shipped', 'cancelled'] as $status)
                    <a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => $status]) }}">
                        {{ ucfirst($status) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            @if($orders->isEmpty())
                <p class="text-center">No orders found.</p>
            @else
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $order->user_id) }}">
                                    {{ $order->user->name ?? 'Deleted User' }}
                                </a>
                            </td>
                            <td>{{ $order->orderItems->count() }}</td>
                            <td>${{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge badge-{{
                                    $order->status == 'cancelled' ? 'danger' :
                                    ($order->status == 'completed' ? 'success' :
                                    ($order->status == 'shipped' ? 'info' : 'warning'))
                                }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Archive this order?')">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/orders.css') }}">
@stop

@section('js')
<script>
    // Status update quick actions could be added here
</script>
@stop
