@extends('adminlte::page')

@section('title', 'Customers')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/admin-style.css') }}">

<div class="admin-section">
    <h2 class="section-header">Customers</h2>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Name</th><th>Email</th><th>Total Orders</th></tr></thead>
            <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->orders_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">
            {{ $customers->links() }}
        </div>
</div>
@endsection
