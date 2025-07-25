@extends('adminlte::page')

@section('title', 'Orders')

@section('content')
    <div class="admin-section">
        <div class="section-header">
            <h2>Order Management</h2>
        </div>

        <div class="section-body">
            <div class="mb-3">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="status-filter">
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Orders</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                        </option>
                    </select>
                </form>
            </div>

            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                            <td>${{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST"
                                    class="status-form">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="status-select" data-order-id="{{ $order->id }}"
                                        data-original="{{ $order->status }}">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid
                                        </option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped
                                        </option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                    <div>
                                        <small class="text-muted">
                                            Last changed: {{ $order->updated_at->format('M d, Y') }}
                                        </small>
                                    </div>
                                </form>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn-icon" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @role('superadmin')
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                                        class="delete-form d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endrole
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination-wrap">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('select.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const form = this.closest('form');
                const orderId = this.dataset.orderId;
                const newStatus = this.value;
                const originalValue = this.dataset.original;

                Swal.fire({
                    title: 'Confirm Status Change',
                    text: `Change order #${orderId} to ${newStatus}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Update',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else {
                        this.value = originalValue;
                    }
                });
            });
        });


        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Delete Order?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Delete',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 2500,
                showConfirmButton: false
            });
        @endif
    </script>
@endsection
