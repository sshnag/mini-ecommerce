@extends('layouts.app')

@section('title', "TIFFANY - {$category->name} Collection")

@push('style')
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
              <!-- Search and Filter Form -->
            <div class="row justify-content-center mb-5">
                <div class="col-md-10">
                    <form method="GET" action="{{ route('categories.show', $category->slug ?? $category->id) }}"
                        class="d-flex flex-wrap gap-3 shadow rounded p-3 mb-4 align-items-end">
                        <!-- Search Input -->
                        <div class="flex-fill">
                            <label for="search" class="form-label small text-muted">Search</label>
                            <input type="text" name="search" id="search" class="form-control search-input"
                                placeholder="Search products by name..." value="{{ request('search') }}">
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="form-label small text-muted">Price Range</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="100" name="min_price" class="form-control"
                                    placeholder="Min" value="{{ request('min_price') }}">
                                <span class="input-group-text">to</span>
                                <input type="number" step="100" name="max_price" class="form-control"
                                    placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>

                        <!-- Sorting Dropdown -->
                        <div>
                            <label for="sort" class="form-label small text-muted">Sort By</label>
                            <select name="sort" id="sort" class="form-select">
                                <option value="price_desc" {{ request('sort', 'price_desc') == 'price_desc' ? 'selected' : '' }}>
                                    Price: High to Low
                                </option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                    Price: Low to High
                                </option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                                    Name: A-Z
                                </option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                                    Name: Z-A
                                </option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                                    Newest First
                                </option>
                                <option value="top_rated" {{ request('sort') == 'top_rated' ? 'selected' : '' }}>
                                    Top Rated
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-gold px-4">Apply</button>
                        @if(request()->hasAny(['search', 'min_price', 'max_price', 'sort']))
                            <a href="{{ route('categories.show', $category->slug ?? $category->id) }}"
                               class="btn btn-outline-secondary px-4">Reset</a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Products -->
            <div class="row">
                @forelse($products as $product)
                    @php
                        $inWishlist = in_array($product->id, $wishlistProductIds);
                        $wishlistItemId = null;
                        if ($inWishlist) {
                            $wishlistItemId = \App\Models\Wishlist::where('product_id', $product->id)
                                ->where(function($query) { if (auth()->check()) { $query->where('user_id', auth()->id()); } else { $query->where('session_id', session()->getId()); } })
                                ->value('id');
                        }
                    @endphp
                    <div class="col-md-4 mb-4">
                        <div class="product-card h-100 position-relative">
                            <a href="{{ route('products.show', $product) }}">
                                <div class="product-image">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="img-fluid">
                                    <div class="product-overlay"></div>
                                </div>
                                <div class="product-details p-2">
                                    <h3>{{ $product->name }}</h3>
                                    <p class="product-price">${{ number_format($product->price, 2) }}</p>
                                    <div class="product-rating" title="Rated {{ number_format($product->reviews->avg('rating'), 1) }} out of 5">
    @php $avg = $product->reviews->avg('rating'); @endphp
    @for ($i = 1; $i <= 5; $i++)
        @if ($avg >= $i)
            <i class="fas fa-star"></i>
        @elseif ($avg >= $i - 0.5)
            <i class="fas fa-star-half-alt"></i>
        @else
            <i class="far fa-star"></i>
        @endif
    @endfor
</div>
                                </div>
                            </a>
                            <button class="wishlist-btn position-absolute top-0 end-0 m-2 btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center"
                                data-product-id="{{ $product->id }}" data-wishlist-id="{{ $wishlistItemId }}"
                                style="width:40px; height:40px; z-index:2;">
                                <i class="fa-heart wishlist-heart {{ $inWishlist ? 'fas filled' : 'far' }}" style="font-size:1.3rem;"></i>
                            </button>
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
        @if (session('success'))
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

        @if (session('error'))
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(e) {
        let btn = e.target.closest('.wishlist-btn');
        if (!btn) return;
        e.preventDefault();
        let heart = btn.querySelector('.wishlist-heart');
        let productId = btn.dataset.productId;
        let wishlistId = btn.dataset.wishlistId;
        // If not filled, add to wishlist
        if (!heart.classList.contains('filled')) {
            fetch("{{ route('wishlist.add') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(res => res.json())
            .then(data => {
                heart.classList.remove('far');
                heart.classList.add('fas', 'filled');
                // Update wishlist count badge in navbar
                var badge = document.getElementById('wishlistBadge');
                if (badge) {
                    badge.textContent = data.count;
                } else if (data.count > 0) {
                    var navLink = document.getElementById('wishlistNavLink');
                    if (navLink) {
                        var span = document.createElement('span');
                        span.id = 'wishlistBadge';
                        span.className = 'wishlist-count badge bg-info text-white position-absolute top-0 start-100 translate-middle rounded-circle d-flex align-items-center justify-content-center';
                        span.style = 'font-size:0.8rem; min-width:1.5em; min-height:1.5em; line-height:1.5em; padding:0; text-align:center; border:2px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,0.08); z-index:10;';
                        span.textContent = data.count;
                        navLink.appendChild(span);
                    }
                }
                // Set wishlist id for removal
                if (data.wishlist_id) {
                    btn.dataset.wishlistId = data.wishlist_id;
                }
                Swal.fire({
                    icon: 'success',
                    title: data.success,
                    confirmButtonColor: '#bfa36f'
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Something went wrong',
                    text: 'Please try again later',
                    confirmButtonColor: '#bfa36f'
                });
                console.error(error);
            });
        } else if (wishlistId) {
            // Remove from wishlist
            fetch(`/wishlist/remove/${wishlistId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                let data;
                try {
                    data = await res.json();
                } catch (e) {
                    console.error('Non-JSON response from wishlist remove', e);
                    return;
                }
                console.log('Remove wishlist response:', data);
                heart.classList.remove('fas', 'filled');
                heart.classList.add('far');
                // Update wishlist count badge in navbar
                var badge = document.getElementById('wishlistBadge');
                if (badge) {
                    badge.textContent = data.count;
                    if (data.count == 0) badge.remove();
                }
                btn.dataset.wishlistId = '';
                Swal.fire({
                    icon: 'success',
                    title: data.success,
                    confirmButtonColor: '#bfa36f'
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Something went wrong',
                    text: 'Please try again later',
                    confirmButtonColor: '#bfa36f'
                });
                console.error(error);
            });
        }
    });
});
</script>
@endpush
