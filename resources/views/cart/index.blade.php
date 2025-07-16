@extends('layouts.app')

@section('title', 'Your Shopping Bag')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endpush

@section('content')
<section class="cart-section py-5">
    <div class="container">
        <h2 class="mb-4">Your Shopping Bag</h2>

        @if($cartItems->isEmpty())
            <div class="alert alert-info">
                Your cart is empty. <a href="{{ route('home') }}" class="btn btn-gold ml-2">Start Shopping</a>
            </div>
        @else
            <table class="table table-borderless cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th class="text-end">Remove</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
<img src="{{ asset('storage/' . $item->product->image) }}" class="img-thumbnail" style="width: 80px;">
                                    <div class="ms-3">
                                                                                    <a href="{{ route('products.show', $item->product->custom_id) }}" class="text-dark text">
                                        <h5 class="mb-1">{{ $item->product->name }}</h5>
                                                                                    </a>
                                        <small>{{ $item->product->category->name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->quantity }}</td>
<td>${{ number_format($item->product->price, 2) }}</td>
                            <td class="text-end">
                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('home') }}" class="btn btn-gold">Continue Shopping</a>
                <div>
                    <h4>Total: ${{ number_format($total, 2) }}</h4>
                    <a href="{{ route('checkout.shipping') }}" class="btn btn-gold">Proceed to Checkout</a>
                </div>
            </div>
        @endif
    </div>
</section>

@if(!$recommended->isEmpty())
<section class="recommended-section py-5 bg-light border-top">
    <div class="container">
        <h4 class="mb-4">You may also like</h4>
        <div class="row">
            @foreach($recommended as $product)
                <div class="col-md-3 mb-4">
    <div class="card h-100 border-0 shadow-sm">
        <a href="{{ route('products.show', $product->custom_id) }}">
            <img src="{{ asset('storage/' . $product->image) }}"
                 class="card-img-top"
                 alt="{{ $product->name }}"
                 style="height: 200px; object-fit: cover;">
        </a>
        <div class="card-body d-flex flex-column">
            <div>
                <a href="{{ route('products.show', $product->custom_id) }}"
                   class="text-dark text-decoration-none">
                    <h6 class="card-title">{{ $product->name }}</h6>
                </a>
                <p class="text-muted mb-2">${{ number_format($product->price, 2) }}</p>
            </div>
            <form action="{{ route('cart.store') }}" method="POST" class="mt-auto">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button class="btn btn-sm btn-gold w-100">Add to Bag</button>
            </form>
        </div>
    </div>
</div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
