@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<div class="row">
    @foreach ($products as $product)
        <div class="col-md-3 position-relative group">
            <div class="card border-0 shadow-sm">
                <a href="{{ route('products.show', $product->id) }}">
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->title }}">
                </a>
                <div class="card-body text-center">
                    <a href="{{ route('products.show', $product->id) }}" class="text-dark text">
                        <h5 class="card-title">{{ $product->title }}</h5>
                    </a>
                    <p class="card-text">${{ $product->price }}</p>

                    {{-- Add to Bag Button (shown always OR on hover) --}}
                    <form action="{{ route('cart.store') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-dark btn-sm mt-2 add-to-bag-btn">
                            Add to Bag
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>


@endsection
