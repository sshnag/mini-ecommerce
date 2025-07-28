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
                            <label for="min_price" class="form-label small text-muted">Price Range</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="100" name="min_price" id="min_price" class="form-control"
                                    placeholder="Min" value="{{ request('min_price') }}">
                                <span class="input-group-text">to</span>
                                <input type="number" step="100" name="max_price" id="max_price" class="form-control"
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
                        // Debug output
                        if ($inWishlist) {
                            echo "<!-- Debug: Product {$product->id} in wishlist, wishlistItemId: " . ($wishlistItemId ?? 'null') . " -->";
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
                                data-product-id="{{ $product->id }}" data-wishlist-id="{{ $wishlistItemId ?? '' }}"
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sync UI state with database state on page load
    syncWishlistState();
    
    // Flag to prevent double requests
    let isProcessing = false;
    
    document.body.addEventListener('click', function(e) {
        let btn = e.target.closest('.wishlist-btn');
        if (!btn || isProcessing) return;
        e.preventDefault();

        let heart = btn.querySelector('.wishlist-heart');
        let productId = btn.dataset.productId;
        let wishlistId = btn.dataset.wishlistId;

        console.log('Click detected:', {
            'productId': productId,
            'wishlistId': wishlistId,
            'heartFilled': heart.classList.contains('filled'),
            'heartClasses': heart.className
        });

        // Prevent double clicks
        isProcessing = true;
        btn.disabled = true;

        // Add animation class
        heart.classList.add('heart-animate');
        setTimeout(() => heart.classList.remove('heart-animate'), 500);

        // Add to wishlist
        if (!heart.classList.contains('filled') || !wishlistId) {
            console.log('Attempting to add to wishlist');
            
            // Get CSRF token
            const csrfToken = '{{ csrf_token() }}';
            console.log('CSRF Token for add:', csrfToken);
            
            fetch("{{ route('wishlist.add') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                console.log('Add response data:', data);
                if (data.success) {
                    // Handle both "Product added to wishlist" and "Already in wishlist" cases
                    heart.classList.remove('far');
                    heart.classList.add('fas', 'filled');
                    btn.dataset.wishlistId = data.wishlist_id;

                    // Only show message if item was actually added, not if it was already in wishlist
                    if (data.success !== 'Already in wishlist') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Added to Wishlist!',
                            text: 'Item has been added to your wishlist',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            background: '#1f1f1f',
                            color: '#fff',
                        });
                    }

                    updateWishlistBadge(data.count);
                } else {
                    throw new Error(data.error || 'Unknown error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add to wishlist',
                    toast: true,
                    position: 'top-end',
                    timer: 3000
                });
            })
            .finally(() => {
                // Re-enable button after processing
                isProcessing = false;
                btn.disabled = false;
            });
        }
        // Remove from wishlist
        else if (wishlistId && wishlistId.trim() !== '' && !isNaN(wishlistId) && parseInt(wishlistId) > 0) {
            const removeUrl = `/wishlist/remove/${wishlistId}`;
            console.log('Attempting to remove from wishlist, wishlistId:', wishlistId);
            console.log('Remove URL:', removeUrl);
            console.log('Full URL:', window.location.origin + removeUrl);
            
            // Get CSRF token
            const csrfToken = '{{ csrf_token() }}';
            console.log('CSRF Token:', csrfToken);
            
            fetch(removeUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(async res => {
                console.log('Remove response status:', res.status);
                console.log('Remove response headers:', res.headers);
                
                // Log the raw response text for debugging
                const responseText = await res.text();
                console.log('Raw response text:', responseText.substring(0, 200) + '...');
                
                if (!res.ok) {
                    console.error('Response not ok:', res.status, res.statusText);
                    throw new Error(`Network response was not ok: ${res.status} ${res.statusText}`);
                }
                
                // Try to parse as JSON
                try {
                    const data = JSON.parse(responseText);
                    console.log('Successfully parsed JSON:', data);
                    return data;
                } catch (parseError) {
                    console.error('JSON parse error:', parseError);
                    console.error('Response was not JSON. Full response:', responseText);
                    throw new Error('Response is not valid JSON. Server returned HTML instead of JSON.');
                }
            })
            .then(data => {
                console.log('Remove response data:', data);
                console.log('data.success type:', typeof data.success);
                console.log('data.success value:', data.success);

                if (data.success) {
                    console.log('Success case - updating UI');
                    heart.classList.remove('fas', 'filled');
                    heart.classList.add('far');
                    btn.dataset.wishlistId = '';

                    Swal.fire({
                        icon: 'success',
                        title: 'Removed from Wishlist!',
                        text: 'Item has been removed from your wishlist',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: '#1f1f1f',
                        color: '#fff',
                    });

                    updateWishlistBadge(data.count);
                } else {
                    console.log('Error case - throwing error');
                    throw new Error(data.error || 'Unknown error');
                }
            })
            .catch(error => {
                console.error('Error details:', {
                    message: error.message,
                    stack: error.stack,
                    type: error.constructor.name
                });
                console.log('Error occurred but item was likely removed - updating UI anyway');
                
                // Update UI even if there was an error, since the item was actually removed
                heart.classList.remove('fas', 'filled');
                heart.classList.add('far');
                btn.dataset.wishlistId = '';
                
                // Try to update badge count
                const currentBadge = document.getElementById('wishlistBadge');
                if (currentBadge) {
                    const currentCount = parseInt(currentBadge.textContent || '0');
                    updateWishlistBadge(Math.max(0, currentCount - 1));
                }
                
                // Show a more specific error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: `Failed to remove from wishlist: ${error.message}`,
                    toast: true,
                    position: 'top-end',
                    timer: 3000
                });
            })
            .finally(() => {
                // Re-enable button after processing
                isProcessing = false;
                btn.disabled = false;
            });
        } else {
            console.log('No valid wishlistId found, but heart is filled. Updating UI to sync with database state.');
            console.log('wishlistId value:', wishlistId, 'type:', typeof wishlistId, 'isNaN:', isNaN(wishlistId));
            
            // Simply update the UI to sync with database state
            heart.classList.remove('fas', 'filled');
            heart.classList.add('far');
            btn.dataset.wishlistId = '';
            
            // Try to update badge count
            const currentBadge = document.getElementById('wishlistBadge');
            if (currentBadge) {
                const currentCount = parseInt(currentBadge.textContent || '0');
                updateWishlistBadge(Math.max(0, currentCount - 1));
            }
            
            Swal.fire({
                icon: 'info',
                title: 'Wishlist Updated',
                text: 'Item removed from wishlist',
                toast: true,
                position: 'top-end',
                timer: 2000
            });
            
            // Re-enable button after processing
            isProcessing = false;
            btn.disabled = false;
        }
    });

    function updateWishlistBadge(count) {
        const badge = document.getElementById('wishlistBadge');
        const navLink = document.getElementById('wishlistNavLink');

        if (count > 0) {
            if (badge) {
                badge.textContent = count;
            } else if (navLink) {
                const span = document.createElement('span');
                span.id = 'wishlistBadge';
                span.className = 'wishlist-count badge bg-info text-white position-absolute top-0 start-100 translate-middle rounded-circle d-flex align-items-center justify-content-center';
                span.style = 'font-size:0.8rem; min-width:1.5em; min-height:1.5em; line-height:1.5em; padding:0; text-align:center; border:2px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,0.08); z-index:10;';
                span.textContent = count;
                navLink.appendChild(span);
            }
        } else if (badge) {
            badge.remove();
        }
    }

    function syncWishlistState() {
        // Get all wishlist buttons and sync their state
        const wishlistButtons = document.querySelectorAll('.wishlist-btn');
        wishlistButtons.forEach(btn => {
            const heart = btn.querySelector('.wishlist-heart');
            const wishlistId = btn.dataset.wishlistId;

            // If button has wishlistId but heart is not filled, sync it
            if (wishlistId && !heart.classList.contains('filled')) {
                heart.classList.remove('far');
                heart.classList.add('fas', 'filled');
            }
            // If button has no wishlistId but heart is filled, sync it
            else if (!wishlistId && heart.classList.contains('filled')) {
                heart.classList.remove('fas', 'filled');
                heart.classList.add('far');
            }
        });
    }
});
</script>
@endpush
