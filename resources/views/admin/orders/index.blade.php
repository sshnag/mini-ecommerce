@extends('adminlte::page')

@section('title', 'Users')

@section('content')
<div class="admin-section">
    <div class="section-header">
        <h2>User Management</h2>
        @can('create users')
        <a href="{{ route('admin.users.create') }}" class="btn-add">
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
                        <form action="{{ route('admin.users.update-roles', $user) }}" method="POST" class="roles-form">
                            @csrf
                            @method('PATCH')
                            <select name="roles[]" class="roles-select" multiple data-user-id="{{ $user->id }}">
                                @foreach($allRoles as $role)
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
                    <td colspan="5" class="text-center">No users found</td>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Additional styles for Select2 to match admin theme */
    .select2-container--default .select2-selection--multiple {
        background-color: #333;
        border: 1px solid #444;
        border-radius: 4px;
        color: #e0e0e0;
        min-height: 38px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #28a745;
        border: 1px solid #228e3b;
        color: white;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #28a745;
    }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Initialize Select2 when DOM is fully loaded
    $(document).ready(function() {
        $('.roles-select').select2({
            placeholder: "Select roles",
            width: '100%',
            dropdownParent: $('.admin-section')
        });

        // Role change confirmation
        document.querySelectorAll('.roles-select').forEach(select => {
            select.addEventListener('change', function() {
                const form = this.closest('form');
                const userId = this.dataset.userId;
                const selectedRoles = Array.from(this.selectedOptions).map(option => option.value);

                Swal.fire({
                    title: 'Confirm Role Update',
                    html: `Update roles for user <strong>#${userId}</strong> to:<br><br>${selectedRoles.map(r => `<span class="badge bg-success">${r}</span>`).join(' ')}`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Update',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else {
                        // Reset to original values
                        $(this).val($(this).data('previous-values')).trigger('change');
                    }
                });
            });

            // Store initial values
            $(select).data('previous-values', $(select).val());
        });

        // Delete confirmation
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

        // Success message
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
    });
</script>
@endsection
