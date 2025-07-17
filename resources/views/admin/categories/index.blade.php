@extends('adminlte::page')

@section('title', 'Categories')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="page-container">
    <div class="page-header">
        <h1>Categories</h1>
    </div>

    <div class="card modern-card">
        <div class="card-body table-container">
            @if($categories->isEmpty())
                <div class="empty-state">No categories found.</div>
            @else
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Product Count</th>
                            @role('superadmin')
                            <th class="text-end">Actions</th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->products_count }}</td>
                                <td class="text-end">
                                    {{-- @can('update', $category)
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-icon warning"><i class="fas fa-edit"></i></a>
                                    @endcan --}}

                                    @can('delete', $category)
                                        <form action="{{ route('superadmin.categories.destroy', $category->id) }}"
                                              method="POST"
                                              class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-icon danger delete-btn"
                                                    data-category="{{ $category->name }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination-wrapper">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // SweetAlert for delete
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('form');
            const categoryName = this.dataset.category;

            Swal.fire({
                title: 'Are you sure?',
                text: `Delete category "${categoryName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
