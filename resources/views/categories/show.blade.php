@extends('layouts.app')

@section('title', "TIFFANY - {$category->name} Collection")

@push('styles')
<link rel="stylesheet" href="{{ asset('css/category.css') }}">
@endpush

@section('content')
<section class="category-header">
    <div class="container text-center">
        <h1 class="category-title">{{ $category->name }}</h1>
        <p class="category-description">Explore our exquisite collection of {{ $category->name }}</p>
    </div>
</section>

<section class="category-products">
    <div class="container">

        <!-- Search Form -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
               <form method="GET" action="{{ route('categories.show', $category->slug ?? $category->id) }}" class="d-flex flex-wrap gap-3 shadow rounded p-3 mb-4">
    <input
        type="text"
        name="search"
        class="form-control search-input flex-fill"
        placeholder="Search products by name    ..."
        value="{{ request('search') }}"
    >

    <div class="input-group mb-2" style="width: 160px;">
    <span class="input-group-text">$</span>
    <input
        type="number"
        step="100"
        name="min_price"
        class="form-control"
        placeholder="Min Price"
        value="{{ request('min_price') }}"
    >
</div>

<div class="input-group mb-2" style="width: 160px;">
    <span class="input-group-text">$</span>
    <input
        type="number"
        step="100"
        name="max_price"
        class="form-control"
        placeholder="Max Price"
        value="{{ request('max_price') }}"
    >
</div>


    <button type="submit" class="btn btn-gold px-4">Filter</button>
</form>

            </div>
        </div>

        <!-- Products -->
        <div class="row">
            @forelse($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="product-card h-100">
                        <a href="{{ route('products.show', $product) }}">
                            <div class="product-image">
<img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid">
                                <div class="product-overlay"></div>
                            </div>
                            <div class="product-details p-2">
                                <h3>{{ $product->name }}</h3>
                                <p class="product-price">${{ number_format($product->price, 2) }}</p>
                                <div class="product-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($product->reviews->avg('rating')))
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                    <span>({{ $product->reviews->count() }})</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">No products found.</p>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $products->links() }}
        </div>

    </div>
</section>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
            toast: true,
            position: 'top-end',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            background: '#1f1f1f',
            color: '#fff',
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ session('error') }}",
            toast: true,
            position: 'top-end',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            background: '#1f1f1f',
            color: '#fff',
        });
    @endif
</script>
@endsection
