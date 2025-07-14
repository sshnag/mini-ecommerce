<nav class="navbar navbar-expand-lg bg-white shadow-sm luxury-navbar">
  <div class="container">
    <a class="navbar-brand luxury-logo" href="{{ route('home') }}">TIFFANY</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav ms-auto text-uppercase fw-bold">
        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('products.all') }}">Shop</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">Cart</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">Orders</a></li>
      </ul>
    </div>
  </div>
</nav>
