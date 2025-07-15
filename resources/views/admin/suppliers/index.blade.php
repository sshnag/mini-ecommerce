@extends('adminlte::page')

@section('title', 'Suppliers')

@section('content')
<div class="admin-section">
    <div class="section-header d-flex justify-content-between align-items-center">
        <h2>Supplier Management</h2>
        @role('superadmin')
        <a href="{{ route('superadmin.users.create') }}" class="btn-add">
            <i class="fas fa-plus"></i> Add Supplier
        </a>
        @endrole
    </div>

    <div class="section-body">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Registered At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $supplier)
                <tr>
                    <td>{{ $supplier->custom_id ?? $supplier->id }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->email }}</td>
                    <td>
                        <span class="badge bg-info">
                            {{ $supplier->getRoleNames()->join(', ') }}
                        </span>
                    </td>
                    <td>{{ $supplier->created_at->format('Y-m-d') }}</td>
                    <td>
                        @role('superadmin')
                        <form action="{{ route('admin.users.destroy', $supplier) }}" method="POST" class="delete-form d-inline">
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
                    <td colspan="6" class="text-center">No suppliers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-wrap">
            {{ $users->links() }}
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
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Delete Supplier?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    @if(session('success'))
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 3000,
        toast: true,
        background: '#1f1f1f',
        color: '#fff'
    });
    @endif
</script>
@endsection
