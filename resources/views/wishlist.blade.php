@extends('layouts.app')

@section('title', 'Your Wishlist')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endpush

@section('content')
<section class="cart-section py-5">
    <div class="container">
        <h2 class="mb-5 text-center">My Wishlist</h2>
        @if ($wishlistItems->count() > 0)
            <div class="row g-4 justify-content-center">
                @foreach($wishlistItems as $item)
                    <div class="col-lg-4 col-md-6 col-sm-10">
                        <div class="card shadow border-0 h-100">
                            <a href="{{ route('products.show', $item->product->custom_id) }}" class="text-decoration-none">
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="card-img-top" alt="{{ $item->product->name }}" style="height:350px; justify-content:center;    ">
                            </a>
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h5 class="card-title mb-2">{{ $item->product->name }}</h5>
                                <p class="card-text mb-3">${{ number_format($item->product->price, 2) }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <a href="{{ route('products.show', $item->product->custom_id) }}" class="btn btn-outline-dark">View</a>
                                    <form method="POST" action="{{ route('wishlist.remove', $item->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <h4 class="text-muted text-center">There are no products in your Wishlist</h4>
            <div class="mt-4 text-center">
                <a href="{{ route('home') }}" class="btn btn-dark">Continue Shopping</a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cartForms = document.querySelectorAll('.add-to-cart-form');

            cartForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
    Swal.fire({
        icon: data.status === 'success' ? 'success' : (data.status === 'info' ? 'info' : 'error'),
        title: data.message || 'Added to Bag!',
        showConfirmButton: false,
        timer: 1500,
        background: '#fffaf2',
        confirmButtonColor: '#d4af37'
    });

    // Update cart count in navbar
    const cartCountEl = document.querySelector('.cart-count');
    if (cartCountEl) {
        cartCountEl.textContent = data.cartCount;
    } else {
        const icon = document.querySelector('.fa-shopping-bag');
        if (icon && data.cartCount > 0) {
            const badge = document.createElement('span');
            badge.className = 'cart-count badge bg-dark rounded-circle position-absolute top-0 start-100 translate-middle';
            badge.textContent = data.cartCount;
            icon.parentElement.appendChild(badge);
        }
    }
})

                    .catch(error => {
                        console.error(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Something went wrong. Please try again.',
                            confirmButtonColor: '#d4af37'
                        });
                    });
                });
            });
        });
    </script>
@endpush
