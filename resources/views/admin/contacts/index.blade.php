@extends('adminlte::page')

@section('title', 'Contact Requests')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="fw-bold text-dark">Contact Requests</h1>
    </div>
@stop

@section('content')
    <div class="card shadow-sm mt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark table-styled">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $contact)
                            <tr>
                                <td>{{ $contact->id }}</td>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ Str::limit($contact->subject, 20) }}</td>
                                <td>{{ Str::limit($contact->message, 50) }}</td>
                                <td>
                                    <form action="{{ route('admin.contacts.updateStatus', $contact->id) }}" method="POST" class="status-form">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm status-dropdown">
    @if ($contact->status == 'new')
        <option value="new" selected>New</option>
        <option value="read">Read</option>
        <option value="replied">Replied</option>
    @elseif($contact->status == 'read')
        <option value="read" selected>Read</option>
        <option value="replied">Replied</option>
    @elseif ($contact->status == 'replied')
        <option value="replied" selected>Replied</option>
    @endif
</select>

                                    </form>
                                </td>
                                <td>{{ $contact->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.contacts.show', $contact->id) }}"
                                        class="btn-icon me-1">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @role('superadmin')
                                    <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endrole
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No contact requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-end">
            {{ $contacts->links() }}
        </div>
    </div>
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- SweetAlert confirm for deleting --}}
    <script>
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This contact message will be archived!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>

    {{-- SweetAlert confirm for status change --}}
    <script>
        document.querySelectorAll('.status-dropdown').forEach(dropdown => {
            dropdown.addEventListener('change', function(e) {
                const form = this.closest('form');
                const newStatus = this.value;

                Swal.fire({
                    title: 'Change status?',
                    text: `Are you sure you want to change the status to "${newStatus}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, change it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else {
                        form.reset(); // Reset dropdown if cancelled
                    }
                });
            });
        });
    </script>

    {{-- SweetAlert success after page reload --}}
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            timer: 2500,
            showConfirmButton: false
        });
    </script>
    @endif
@stop
