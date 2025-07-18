@extends('adminlte::page')

@section('title', 'Add New User')

@section('content')
    <div class="admin-section">
        <div class="section-header mb-4 d-flex justify-content-between align-items-center">
            <h2>Add New User</h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Users ListS
            </a>
        </div>

        <div class="section-body">
            <form method="POST" action="{{ route('superadmin.users.store') }}" id="userCreateForm">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ session('form_reset') ? '' : old('name') }}">
                    @error('name')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email" class="form-control"
                        value="{{ session('form_reset') ? '' : old('email') }}">
                    @error('email')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" id="password" name="password" class="form-control">
                    @error('password')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password <span
                            class="text-danger">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                </div>

                <div class="mb-4">
                    <label for="roles" class="form-label">Assign Roles</label>
                    <select name="roles[]" id="roles" class="form-select role-select" multiple>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}"
                                {{ session('form_reset') ? '' : (in_array($role->name, old('roles', [])) ? 'selected' : '') }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('roles')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Create User
                </button>
            </form>
        </div>
    </div>
@endsection

@section('css')
    <!-- Add this line before your other CSS links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.role-select').select2({
                placeholder: 'Select roles',
                width: '100%'
            });

            @if (session('success'))
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Supplier created successfully',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    background: '#1f1f1f',
                    color: '#fff'
                });

                // Programmatically reset the form
                document.getElementById('userCreateForm').reset();
            @endif
        });
    </script>
@endsection
