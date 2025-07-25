@extends('layouts.app')

@section('title', 'Your Wishlist')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endpush

@section('content')
<section class="cart-section py-5">
    <div class="container">
        <h2 class="mb-4">My Wishlist</h2>

        @if ($wishlistItems->count() > 0)
            <table class="table table-borderless cart-table align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
            <tbody>
  @foreach($wishlistItems as $item)
    <div>
        <h4>{{ $item->product->name }}</h4>
        <p>${{ number_format($item->product->price, 2) }}</p>
        <a href="{{ route('products.show', $item->product->id) }}">View</a>
        <form method="POST" action="{{ route('wishlist.remove', $item->id) }}">
            @csrf
            @method('DELETE')
            <button type="submit">Remove</button>
        </form>
    </div>
@endforeach

</tbody>


            </table>
        @else
            <h4 class="text-muted">There are no products in your Wishlist</h4>
            <div class="mt-4">
                <a href="{{ route('home') }}" class="btn btn-gold">Continue Shopping</a>
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
