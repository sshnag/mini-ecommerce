@extends('layouts.app')

@section('title', 'Your Shopping Bag')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show SweetAlert for any cart errors
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d4af37'
                });
            @endif

            // Add event listener for all add-to-cart buttons
            document.querySelectorAll('.add-to-cart-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const productId = this.querySelector('[name="product_id"]').value;
                    const quantity = parseInt(this.querySelector('[name="quantity"]').value);
                    const productStock = parseInt(this.dataset.stock);

                    if (quantity > productStock) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Stock Limit Exceeded',
                            text: `Only ${productStock} items available in stock`,
                            confirmButtonColor: '#d4af37'
                        });
                        return;
                    }

                    // If stock is OK, submit the form
                    this.submit();
                });
            });
        });
    </script>
@endpush

@section('content')
    <section class="cart-section py-5">
        <div class="container">
            <h2 class="mb-4">My WishList</h2>

            @if ($cartItems->isEmpty())
                <div class="alert alert-info">
                    Your wishlist is empty. <a href="{{ route('home') }}" class="btn btn-gold ml-2">Add items to Wishlist</a>
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
                            <tr class="{{ $item->quantity > $item->product->stock ? 'table-warning' : '' }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $item->product->image) }}" class="img-thumbnail"
                                            style="width: 80px;">
                                        <div class="ms-3">
                                            <a href="{{ route('products.show', $item->product->custom_id) }}"
                                                class="text-dark text">
                                                <h5 class="mb-1">{{ $item->product->name }}</h5>
                                            </a>
                                            <small>{{ $item->product->category->name }}</small>
                                            @if($item->quantity > $item->product->stock)
                                                <div class="text-danger small mt-1">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    Only {{ $item->product->stock }} available
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number"
                                               name="quantity"
                                               value="{{ $item->quantity }}"
                                               min="1"
                                               max="{{ $item->product->stock }}"
                                               class="form-control form-control-sm d-inline-block"
                                               style="width: 70px;">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
                                </td>
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
                </div>
            @endif
        </div>
    </section>


@endsection
