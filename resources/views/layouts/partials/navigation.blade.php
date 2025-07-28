<meta name="csrf-token" content="{{ csrf_token() }}">
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
    <div class="container">
        <a class="navbar-brand fs-2" href="/">TIFFANY</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('categories.show', 'rings') }}">Rings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('categories.show', 'necklaces') }}">Necklaces</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('categories.show', 'bracelets') }}">Bracelets</a>
                </li>

                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.index') }}">Profile</a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                @endauth

                <!-- Wishlist Icon with Count -->
                <li class="nav-item position-relative d-flex align-items-center">
                    <a href="{{ route('wishlist.index') }}" class="nav-link position-relative p-0 me-2" id="wishlistNavLink"
                       style="width:44px; height:44px; display:flex; align-items:center; justify-content:center; background:#fff; border-radius:50%; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                        <i class="fas fa-heart" style="font-size:1.3rem; color:#bfa36f;"></i>
                        @if($wishlistCount > 0)
                        <span id="wishlistBadge" class="wishlist-count badge bg-info text-white position-absolute top-0 start-100 translate-middle rounded-circle d-flex align-items-center justify-content-center"
                              style="font-size:0.8rem; min-width:1.5em; min-height:1.5em; line-height:1.5em; padding:0; text-align:center; border:2px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,0.08); z-index:10;">
                            {{ $wishlistCount }}
                        </span>
                        @endif
                    </a>
                </li>

                <!-- Cart Icon with Count -->
                <li class="nav-item position-relative d-flex align-items-center">
                    <a class="nav-link position-relative p-0" href="{{ route('cart.index') }}"
                       style="width:44px; height:44px; display:flex; align-items:center; justify-content:center; background:#fff; border-radius:50%; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                        <i class="fas fa-shopping-bag" style="font-size:1.2rem; color:#bfa36f;"></i>
                        @if($cartCount > 0)
                            <span id="cartBadge" class="cart-count badge bg-dark rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 start-100 translate-middle"
                                  style="font-size:0.8rem; min-width:1.5em; min-height:1.5em; line-height:1.5em; padding:0; text-align:center; border:2px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,0.08); z-index:10;">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Remove wishlist badge from DOM on wishlist page
if (window.location.pathname === '/wishlist') {
    var badge = document.getElementById('wishlistBadge');
    if (badge) badge.remove();
}

// Function to update both wishlist and cart counts
function updateNavCounts(wishlistCount, cartCount) {
    // Update wishlist badge
    const wishlistBadge = document.getElementById('wishlistBadge');
    const wishlistNavLink = document.getElementById('wishlistNavLink');

    if (wishlistCount > 0) {
        if (wishlistBadge) {
            wishlistBadge.textContent = wishlistCount;
        } else if (wishlistNavLink) {
            const span = document.createElement('span');
            span.id = 'wishlistBadge';
            span.className = 'wishlist-count badge bg-info text-white position-absolute top-0 start-100 translate-middle rounded-circle d-flex align-items-center justify-content-center';
            span.style.cssText = 'font-size:0.8rem; min-width:1.5em; min-height:1.5em; line-height:1.5em; padding:0; text-align:center; border:2px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,0.08); z-index:10;';
            span.textContent = wishlistCount;
            wishlistNavLink.appendChild(span);
        }
    } else if (wishlistBadge) {
        wishlistBadge.remove();
    }

    // Update cart badge
    const cartBadge = document.getElementById('cartBadge');
    const cartLink = document.querySelector('a[href="{{ route('cart.index') }}"]');

    if (cartCount > 0) {
        if (cartBadge) {
            cartBadge.textContent = cartCount;
        } else if (cartLink) {
            const span = document.createElement('span');
            span.id = 'cartBadge';
            span.className = 'cart-count badge bg-dark rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 start-100 translate-middle';
            span.style.cssText = 'font-size:0.8rem; min-width:1.5em; min-height:1.5em; line-height:1.5em; padding:0; text-align:center; border:2px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,0.08); z-index:10;';
            span.textContent = cartCount;
            cartLink.appendChild(span);
        }
    } else if (cartBadge) {
        cartBadge.remove();
    }
}

// Make the function available globally
window.updateNavCounts = updateNavCounts;

// Handle AJAX cart operations
document.addEventListener('DOMContentLoaded', function() {
    // Wishlist button handling
    document.body.addEventListener('click', function(e) {
        const wishlistBtn = e.target.closest('.wishlist-btn');
        if (!wishlistBtn) return;
        e.preventDefault();

        const heart = wishlistBtn.querySelector('.wishlist-heart');
        const productId = wishlistBtn.dataset.productId;
        const wishlistId = wishlistBtn.dataset.wishlistId;

        // Add animation class
        heart.classList.add('heart-animate');
        setTimeout(() => heart.classList.remove('heart-animate'), 500);

        // Add to wishlist
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
                if (data.success) {
                    heart.classList.remove('far');
                    heart.classList.add('fas', 'filled');
                    wishlistBtn.dataset.wishlistId = data.wishlist_id;

                    // Update counts
                    if (typeof updateNavCounts === 'function') {
                        updateNavCounts(data.count, {{ $cartCount ?? 0 }});
                    }

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
            });
        }
        // Remove from wishlist
        else if (wishlistId) {
            fetch(`/wishlist/remove/${wishlistId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    heart.classList.remove('fas', 'filled');
                    heart.classList.add('far');
                    wishlistBtn.dataset.wishlistId = '';

                    // Update counts
                    if (typeof updateNavCounts === 'function') {
                        updateNavCounts(data.count, {{ $cartCount ?? 0 }});
                    }

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
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to remove from wishlist',
                    toast: true,
                    position: 'top-end',
                    timer: 3000
                });
            });
        }
    });
});
</script>
@endpush
