@extends('adminlte::page')

@section('title', 'User Management')

@section('content')
<div class="admin-section">
    <div class="section-header">
        <h2>User Management</h2>
    </div>

    <div class="section-body">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Current Roles</th>
                    <th>Change Roles</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach ($user->roles as $role)
                            <span class="badge bg-dark">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.update-roles', $user) }}" class="role-update-form">
                            @csrf
                            @method('PATCH')
                            <select name="roles[]" class="form-select role-select" multiple data-user-name="{{ $user->name }}">
                                @foreach ($allRoles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ $user->roles->contains('name', $role->name) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-dark btn-sm mt-2">Update</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-wrap mt-3">
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
    // Handle form submit with confirmation
    document.querySelectorAll('.role-update-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const userName = this.querySelector('.role-select').dataset.userName;

            Swal.fire({
                title: `Update roles for ${userName}?`,
                text: "This will overwrite current roles.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, update',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    // Flash success message
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
    @endif
</script>
@endsection
