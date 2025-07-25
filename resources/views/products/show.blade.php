@extends('layouts.app')

@section('title', $product->name)

@push('style')
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const qtyInput = document.getElementById('quantityInput');
    const addToCartForm = document.getElementById('addToCartForm');
    const productStock = {{ $product->stock }};

    // Quantity controls
    decreaseBtn.addEventListener('click', () => {
      let current = parseInt(qtyInput.value);
      if (current > 1) {
        qtyInput.value = current - 1;
      }
    });

    increaseBtn.addEventListener('click', () => {
      let current = parseInt(qtyInput.value);
      if (current < productStock) {
        qtyInput.value = current + 1;
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Stock Limit',
          text: `Only ${productStock} items available in stock`,
          confirmButtonColor: '#d4af37'
        });
      }
    });

    // Form submission handler
    addToCartForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const quantity = parseInt(qtyInput.value);

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
</script>
@php
    $inWishlist = false;
    if (Auth::check()) {
        $inWishlist = \App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
    } else {
        $inWishlist = \App\Models\Wishlist::where('session_id', session()->getId())->where('product_id', $product->id)->exists();
    }
@endphp
<script>
document.addEventListener('DOMContentLoaded', function () {
    const wishListBtn = document.getElementById('addToWishList');
    const heartIcon = document.getElementById('wishlistHeart');
    let isFilled = heartIcon.classList.contains('filled');

    if (wishListBtn) {
        wishListBtn.addEventListener('mouseenter', function () {
            if (!isFilled) heartIcon.classList.add('wishlist-heart-hover');
        });
        wishListBtn.addEventListener('mouseleave', function () {
            if (!isFilled) heartIcon.classList.remove('wishlist-heart-hover');
        });
        wishListBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (isFilled) return; // Prevent duplicate add
            const productId = document.querySelector('.product_id').value;

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
                Swal.fire({
                    icon: 'success',
                    title: data.success,
                    confirmButtonColor: '#bfa36f'
                });
                // Animate and fill heart
                heartIcon.classList.remove('far');
                heartIcon.classList.add('fas', 'filled');
                isFilled = true;
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
        });
    }
});
</script>

@endpush

@section('content')
<div class="container py-5">
  <div class="row">
    <div class="col-md-6">
        @if($product->image && Storage::disk('public')->exists($product->image))
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
      @else
        <img src="{{ asset('images/default-product.png') }}" alt="No image" class="img-fluid rounded">
      @endif
    </div>
    <div class="col-md-6">
      <h1 class="mb-3">{{ $product->name }}</h1>
      <p class="h4 text-muted mb-3">${{ number_format($product->price, 2) }}</p>
      <p>{{ $product->description }}</p>

      <form action="{{ route('cart.store') }}" method="POST" class="d-flex align-items-center gap-2 mt-4" id="addToCartForm">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="quantity-control d-flex align-items-center">
          <button type="button" id="decreaseQty" class="btn btn-outline-dark">-</button>
          <input type="text" name="quantity" id="quantityInput" value="1" readonly class="form-control text-center mx-2" style="width: 60px;" max="{{ $product->stock }}">
          <button type="button" id="increaseQty" class="btn btn-outline-dark">+</button>
        </div>

       <button type="submit" class="btn btn-gold @if($product->stock < 1) disabled @endif">
    @if($product->stock < 1) Out of Stock @else Add to Bag @endif
</button>
      </form>
<div class="product_data text-end">
    <input type="hidden" class="product_id" value="{{ $product->id }}">
    <a href="#" id="addToWishList">
        <i class="fa-heart wishlist-heart{{ $inWishlist ? ' fas filled' : ' far' }}" id="wishlistHeart"></i>
    </a>
</div>

      <a href="{{route('home') }}" class="btn btn-gold mt-4 mb-4 d-flex" role="button">Back</a>
      <!-- Average Rating -->
<div class="product-rating mb-3" title="Rated {{ number_format($product->reviews->avg('rating'), 1) }} out of 5">
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
    <span>({{ $product->reviews->count() }})</span>
</div>

<!-- Review Form (only for logged-in users who haven't reviewed) -->
@auth
    @if(!$product->reviews->where('user_id', auth()->id())->count())
        {{--
            IMPORTANT: This form must use POST. Do NOT link to the rate route directly.
            Submitting this form will POST to /products/{product}/rate.
            Visiting that URL directly (GET) will result in a MethodNotAllowed error.
        --}}
        <form method="POST" action="{{ route('products.rate', $product) }}" class="mb-4">
            @csrf
            <div class="star-rating mb-2" style="display: flex; flex-direction: row-reverse; justify-content: flex-end;">
                @for ($i = 5; $i >= 1; $i--)
                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" style="display:none;" tabindex="{{ 6 - $i }}" />
                    <label for="star{{ $i }}" style="cursor:pointer; font-size:2rem; color:#ccc; margin:0 2px;">
                        <i class="fas fa-star"></i>
                    </label>
                @endfor
            </div>
            <textarea name="comment" class="form-control mb-2" placeholder="Write your review (optional)"></textarea>
            <button type="submit" class="btn btn-gold">Submit Review</button>
        </form>
        <script>
        // Highlight selected stars after form submission
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating input[type="radio"]');
            stars.forEach(function(star) {
                star.addEventListener('change', function() {
                    let val = parseInt(this.value);
                    stars.forEach(function(s) {
                        let label = document.querySelector('label[for="' + s.id + '"]');
                        if (parseInt(s.value) <= val) {
                            label.style.color = '#ffc107';
                        } else {
                            label.style.color = '#ccc';
                        }
                    });
                });
            });
        });
        </script>
    @endif
@endauth

<!-- List of All Reviews -->
<div class="reviews-list">
    <h4>Customer Reviews</h4>
    @forelse($product->reviews as $review)
        <div class="review mb-3 p-2 border rounded">
            <strong>{{ $review->user ? $review->user->name : 'Deleted User' }}</strong>
            <span>
                @for ($i = 1; $i <= 5; $i++)
                    <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                @endfor
            </span>
            <p class="mb-1">{{ $review->comment }}</p>
            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
        </div>
    @empty
        <p class="text-muted">No reviews yet. Be the first to review this product!</p>
    @endforelse
</div>
    </div>
  </div>
</div>
@endsection
