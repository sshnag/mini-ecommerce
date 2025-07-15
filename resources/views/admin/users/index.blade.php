@extends('adminlte::page')

@section('title', 'Users')

@section('content')
<div class="admin-section">
    <div class="section-header">
        <h2>User Management</h2>

        @can('create users')
        <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Add User
        </a>
        @endcan
    </div>

    <div class="section-body">
        <form method="GET" action="{{ route('superadmin.users.index') }}" class="filter-form mb-3">
    <div style="display: flex; gap: 10px; align-items: center;">
        <label for="role">Filter by Role:</label>
        <select name="role" id="role" class="form-select" onchange="this.form.submit()">
            <option value="">All Roles</option>
            @foreach($allRoles as $role)
                <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                    {{ ucfirst($role->name) }}
                </option>
            @endforeach
        </select>
    </div>
</form>

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
                @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.update-roles', $user) }}" class="roles-form">
                            @csrf
                            @method('PATCH')
                            <select name="roles[]" class="role-select" multiple data-user-name="{{ $user->name }}">
                                @foreach ($allRoles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-icon" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>

                        @role('superadmin')
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="delete-form d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endrole
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

      <div class="pagination-wrap">
    {{ $users->appends(['role' => request('role')])->links() }}
</div>

    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple {
        background-color: #1f1f1f;
        border: 1px solid #444;
        border-radius: 4px;
        color: #eee;
        min-height: 36px;
    }
    .select2-selection__choice {
        background-color: #28a745 !important;
        border: none !important;
        color: #fff;
        padding: 3px 8px;
        margin-top: 4px;
    }
    .select2-selection__choice__remove {
        color: #fff !important;
    }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.role-select').select2({
            placeholder: 'Select roles',
            width: '100%'
        });

    let isCancelling = false;

$('.role-select').on('change', function () {
    if (isCancelling) {
        isCancelling = false;
        return;
    }

    const select = $(this);
    const form = select.closest('form');
    const userName = select.data('user-name');
    const selectedRoles = select.val();

    Swal.fire({
        title: `Update Roles for ${userName}?`,
        html: `New roles: <strong>${selectedRoles.join(', ')}</strong>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, update',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        } else {
            // Prevent triggering change again
            isCancelling = true;
            select.val(select.data('previous')).trigger('change.select2');
        }
    });
}).each(function () {
    $(this).data('previous', $(this).val());
});


        $('.delete-form').on('submit', function (e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: 'Delete this user?',
                text: 'This action cannot be undone.',
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

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            background: '#1f1f1f',
            color: '#fff'
        });
        @endif
    });
</script>
@endsection
