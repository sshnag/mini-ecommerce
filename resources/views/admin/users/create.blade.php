@extends('adminlte::page')
@section('title', 'Create New Supplier')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('css/admin/supplier.css') }}">
@endsection

@section('content')
<div class="supplier-container">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="card supplier-card">
                    <div class="card-header supplier-header text-center">
                        <h3 class="mb-0">
                            <i class="bi bi-person-plus-fill me-2"></i>
                            Create New Supplier
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <form id="supplier-form" action="{{ route('superadmin.suppliers.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="bi bi-person me-1"></i>
                                    Supplier Name
                                </label>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter supplier name"
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-1"></i>
                                    Email Address
                                </label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Enter email address"

                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-1"></i>
                                    Password
                                </label>
                                <div class="input-group">
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Enter password"
                                        minlength="8"
                                    >
                                    <button class="btn btn-outline-secondary password-toggle" type="button" onclick="togglePassword('password')">
                                        <i class="bi bi-eye" id="password-icon"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Minimum 8 characters</small>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>
                                    Confirm Password
                                </label>
                                <div class="input-group">
                                    <input
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        type="password"
                                        class="form-control"
                                        placeholder="Confirm password"
                                        minlength="8"
                                    >
                                    <button class="btn btn-outline-secondary password-toggle" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="bi bi-eye" id="password_confirmation-icon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg supplier-submit-btn" id="submit-btn">
                                    <span id="button-text">
                                        <i class="bi bi-plus-circle me-2"></i>
                                        Create Supplier
                                    </span>
                                    <span id="loading-spinner" class="spinner-border spinner-border-sm ms-2" style="display: none;" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

// Form submission with validation
document.getElementById('supplier-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const submitBtn = document.getElementById('submit-btn');
    const buttonText = document.getElementById('button-text');
    const loadingSpinner = document.getElementById('loading-spinner');

    // Validate passwords match
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;

    if (password !== confirmPassword) {
        Swal.fire({
            icon: 'error',
            title: 'Password Mismatch',
            text: 'The passwords you entered do not match. Please try again.',
            confirmButtonColor: '#dc3545'
        });
        return;
    }

    // Show loading state
    submitBtn.disabled = true;
    buttonText.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creating Supplier...';
    loadingSpinner.style.display = 'inline-block';

    // Submit form
    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Supplier created successfully',
                confirmButtonColor: '#006b54'
            }).then(() => {
                window.location.href = '{{ route("superadmin.suppliers.index") }}';
            });
        } else {
            // Handle validation errors
            if (data.errors) {
                let errorMessages = [];
                for (const [field, messages] of Object.entries(data.errors)) {
                    errorMessages.push(messages.join('<br>'));
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: errorMessages.join('<br>'),
                    confirmButtonColor: '#dc3545'
                });
            } else {
                throw new Error(data.message || 'Failed to create supplier');
            }
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'An error occurred while creating the supplier',
            confirmButtonColor: '#dc3545'
        });
    })
    .finally(() => {
        submitBtn.disabled = false;
        buttonText.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Create Supplier';
        loadingSpinner.style.display = 'none';
    });
});

// Clear validation errors on input
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('input', function() {
        if (this.classList.contains('is-invalid')) {
            this.classList.remove('is-invalid');
            const errorElement = this.nextElementSibling;
            if (errorElement && errorElement.classList.contains('invalid-feedback')) {
                errorElement.remove();
            }
        }
    });
});
</script>
@endsection
