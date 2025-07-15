@extends('adminlte::page')

@section('title', 'Suppliers')

@section('content')
<div class="admin-section">
    <div class="section-header">
        <h2>Supplier Management</h2>
        @can('create suppliers')
        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Supplier
        </a>
        @endcan
    </div>

    <div class="section-body">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Logo</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Products</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->id }}</td>
                    <td>
                        <img src="{{ asset('storage/'.$supplier->logo) }}"
                             alt="{{ $supplier->name }}" class="thumb-img">
                    </td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->contact_email }}</td>
                    <td>{{ $supplier->products_count }}</td>
                    <td>
                        <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                           class="btn-icon" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>

                        @role('superadmin')
                        <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}"
                              method="POST" class="delete-form d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endrole
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">No suppliers found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-wrap">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
@endsection

@section('js')
<!-- Same SweetAlert JS as users index -->
@endsection
