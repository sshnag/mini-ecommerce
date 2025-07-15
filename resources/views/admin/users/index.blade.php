@extends('adminlte::page')

@section('title', 'Users')

@section('content')
<div class="admin-section">
    <div class="section-header">
        <h2>User Management</h2>
        @can('create users')
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add User
        </a>
        @endcan
    </div>

    <div class="section-body">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach($user->roles as $role)
                            <span class="badge bg-info">{{ ucfirst($role->name) }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}"
                           class="btn-icon" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>

                        @role('superadmin')
                        <form action="{{ route('admin.users.destroy', $user->id) }}"
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
                    <td colspan="5">No users found</td>
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
                title: 'Delete User?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@endsection
