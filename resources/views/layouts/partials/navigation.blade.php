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
                 <li class="nav-item position-relative d-flex align-items-center">
                    <a href="{{url('wishlist')}}" class="nav-link position-relative p-0 me-2" id="wishlistNavLink" style="width:44px; height:44px; display:flex; align-items:center; justify-content:center; background:#fff; border-radius:50%; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                        <i class="fas fa-heart" style="font-size:1.3rem; color:#bfa36f;"></i>
                        @if ($wishlistCount>0)
                        <span id="wishlistBadge" class="wishlist-count badge bg-info text-white position-absolute top-0 start-100 translate-middle rounded-circle d-flex align-items-center justify-content-center" style="font-size:0.8rem; min-width:1.5em; min-height:1.5em; line-height:1.5em; padding:0; text-align:center; border:2px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,0.08); z-index:10;">
                            {{$wishlistCount}}
                        </span>
                        @endif
                    </a>
                </li>
                <li class="nav-item position-relative d-flex align-items-center">
                    <a class="nav-link position-relative p-0" href="{{ route('cart.index') }}" style="width:44px; height:44px; display:flex; align-items:center; justify-content:center; background:#fff; border-radius:50%; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                        <i class="fas fa-shopping-bag" style="font-size:1.2rem; color:#bfa36f;"></i>
                        @if ($cartCount > 0)
                            <span class="cart-count badge bg-dark rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 start-100 translate-middle" style="font-size:0.8rem; min-width:1.5em; min-height:1.5em; line-height:1.5em; padding:0; text-align:center; border:2px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,0.08); z-index:10;">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>

@push('scripts')
<script>
// Remove wishlist badge from DOM on wishlist page
if (window.location.pathname === '/wishlist') {
    var badge = document.getElementById('wishlistBadge');
    if (badge) badge.remove();
}
</script>
@endpush
