@extends('layouts.app')

@section('title', "TIFFANY - {$category->name} Collection")

@push('styles')
<link rel="stylesheet" href="{{ asset('css/category.css') }}">
@endpush

@section('content')
<section class="category-header">
    <div class="container">
        <h1 class="category-title">{{ $category->name }}</h1>
        <p class="category-description">Explore our exquisite collection of {{ $category->name }}</p>
    </div>
</section>

<section class="category-products">
    <div class="container">
        <!-- Size filter for rings and bracelets -->
        @if(!empty($sizePresets))
        <div class="size-filter mb-5">
            <h3>Filter by Size</h3>
            <div class="size-options">
                @foreach($sizePresets as $size)
                <button class="size-option">{{ $size }}</button>
                @endforeach
            </div>
        </div>
        @endif

        <div class="row">
            @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="product-card">
                    <a href="{{ route('products.show', $product) }}">
                        <div class="product-image">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid">
                            <div class="product-overlay"></div>
                        </div>
                        <div class="product-details">
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
            @endforeach
        </div>

        <div class="pagination-wrapper">
            {{ $products->links() }}
        </div>
    </div>
</section>
@endsection
