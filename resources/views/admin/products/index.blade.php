@extends('adminlte::page')

@section('title', 'Products')

@section('content')
    <div class="admin-section">
        <div class="section-header">
            <h2>Product Inventory</h2>


            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Add Product</a>
        </div>

        <div class="section-body">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Preview</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price ($)</th>
                        <th>Stock</th>

                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->custom_id }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="thumb-img">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td>{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                <a href="{{ route('admin.products.show', $product->custom_id) }}" class="btn-icon" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product->custom_id) }}" class="btn-icon"
                                    title="Edit">
                                    <i class="fas fa-edit"></i></a>

                                @can('delete', $product)
                                    <form action="{{ route('superadmin.products.destroy', $product->custom_id) }}"
                                        method="POST" class="delete-form d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination-wrap">
                {{ $products->links() }}
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
        // Intercept delete button
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This product will be archieved!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, archieve it!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
        @if (session('success'))
            <
            script >
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    timer: 2500,
                    showConfirmButton: false
                });
    </script>
    @endif

    </script>
@endsection
