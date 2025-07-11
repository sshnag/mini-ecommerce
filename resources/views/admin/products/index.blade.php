@extends('adminlte::page')

@section('title', 'All Products')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">All Products</h2>
    <div class="row">
        @forelse ($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 product-card position-relative">
                <a href="{{ route('products.show', $product->id) }}">
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                </a>
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="text-muted">${{ number_format($product->price, 2) }}</p>

                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">

                        @if($product->category->size_type === 'ring')
                            <select name="size" required class="form-select mb-2">
                                <option value="">Select ring size (cm)</option>
                                @foreach([4.5, 5, 6, 6.5, 7, 8] as $size)
                                    <option value="{{ $size }}">{{ $size }} cm</option>
                                @endforeach
                            </select>
                        @elseif($product->category->size_type === 'bracelet')
                            <select name="size" required class="form-select mb-2">
                                <option value="">Select bracelet size</option>
                                @foreach(['S','M','L','XL'] as $size)
                                    <option value="{{ $size }}">{{ $size }}</option>
                                @endforeach
                            </select>
                        @endif

                        <button type="submit" class="btn btn-dark w-100">Add to Bag</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center">No products found.</p>
        @endforelse
    </div>
</div>
@endsection
