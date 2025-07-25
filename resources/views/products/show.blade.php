@extends('layouts.app')

@section('title', $product->name)

@push('style')
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize edit modal
    const editModalEl = document.getElementById('editReviewModal');
    let editModal = null;
    if (editModalEl) {
      editModal = new bootstrap.Modal(editModalEl);
    }

    // Edit button handler
    document.querySelectorAll('.edit-review-btn').forEach(button => {
        button.addEventListener('click', function() {
            const reviewId = this.dataset.reviewId;
            const review = JSON.parse(this.dataset.review);

            document.getElementById('editReviewId').value = reviewId;
            document.getElementById('editComment').value = review.comment;

            // Set rating stars
            document.querySelectorAll('#editReviewForm input[name="rating"]').forEach(star => {
                star.checked = parseInt(star.value) === review.rating;
                const label = document.querySelector(`label[for="${star.id}"]`);
                if (label) label.style.color = parseInt(star.value) <= review.rating ? '#ffc107' : '#ccc';
            });

            // Set modal stars
            setEditStars(review.rating);
            if (editModal) editModal.show();
        });
    });

    // Star rating in modal (no checkboxes, just icons)
    const editStars = document.querySelectorAll('#editStarRating .edit-modal-star');
    const editRatingValue = document.getElementById('editRatingValue');
    function setEditStars(val) {
        editStars.forEach(star => {
            star.style.color = parseInt(star.dataset.value) <= val ? '#ffc107' : '#ccc';
        });
        editRatingValue.value = val;
    }
    editStars.forEach(star => {
        star.addEventListener('click', function() {
            setEditStars(this.dataset.value);
        });
    });
    // When opening modal, set stars
    document.querySelectorAll('.edit-review-btn').forEach(button => {
        button.addEventListener('click', function() {
            const review = JSON.parse(this.dataset.review);
            setEditStars(review.rating);
        });
    });

    // Edit form submission
    document.getElementById('editReviewForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const reviewId = formData.get('review_id');

        fetch(`/reviews/${reviewId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PUT'
            },
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update review in UI
                const reviewElement = document.querySelector(`.review[data-review-id="${reviewId}"]`);
                if (reviewElement) {
                    reviewElement.querySelector('.review-rating').innerHTML = '';
                    for (let i = 1; i <= 5; i++) {
                        const star = document.createElement('i');
                        star.className = i <= data.review.rating ? 'fas fa-star' : 'far fa-star';
                        reviewElement.querySelector('.review-rating').appendChild(star);
                    }

                    reviewElement.querySelector('.review-comment').textContent = data.review.comment;
                    reviewElement.querySelector('.review-time').textContent = 'Updated ' + data.review.updated_at;
                }

                if (editModal) editModal.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Review updated!',
                    confirmButtonColor: '#bfa36f'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Update failed',
                text: 'Please try again',
                confirmButtonColor: '#bfa36f'
            });
        });
    });

    // Delete review handler
    document.querySelectorAll('.delete-review-btn').forEach(button => {
        button.addEventListener('click', function() {
            const reviewId = this.dataset.reviewId;

            Swal.fire({
                title: 'Delete review?',
                text: "You won't be able to undo this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#bfa36f',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/reviews/${reviewId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`.review[data-review-id="${reviewId}"]`).remove();
                            Swal.fire({
                                icon: 'success',
                                title: 'Review deleted!',
                                confirmButtonColor: '#bfa36f'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Delete failed',
                            text: 'Please try again',
                            confirmButtonColor: '#bfa36f'
                        });
                    });
                }
            });
        });
    });
});
</script>
@endpush

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-5 col-md-6 mb-4">
      <div class="card shadow-sm border-0 p-3">
        @if($product->image && Storage::disk('public')->exists($product->image))
          <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
        @else
          <img src="{{ asset('images/default-product.png') }}" alt="No image" class="img-fluid rounded">
        @endif
      </div>
    </div>
    <div class="col-lg-7 col-md-6">
      <div class="card shadow-sm border-0 p-4">
        <h1 class="mb-3">{{ $product->name }}</h1>
        <p class="h4 text-muted mb-3">${{ number_format($product->price, 2) }}</p>
        <p class="mb-4">{{ $product->description }}</p>
        <form action="{{ route('cart.store') }}" method="POST" class="d-flex align-items-center gap-2 mb-3" id="addToCartForm">
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
         <div class="d-flex justify-content-end align-items-center mb-3 gap-2" style="position:relative; top:-12px;">
           <a href="#" id="addToWishList" class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:44px; height:44px; box-shadow:0 2px 8px rgba(0,0,0,0.07);">
             <i class="fa-heart wishlist-heart{{ $inWishlist ? ' fas filled' : ' far' }}" id="wishlistHeart" style="font-size:1.5rem;"></i>
           </a>
           <a href="{{route('home') }}" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width:44px; height:44px;" role="button" title="Back">
             <i class="fas fa-arrow-left"></i>
           </a>
         </div>
        <div class="product-rating mb-3" title="Rated {{ number_format($product->reviews->avg('rating'), 1) }} out of 5">
          @php $avg = $product->reviews->avg('rating'); @endphp
          @for ($i = 1; $i <= 5; $i++)
            @if ($avg >= $i)
              <i class="fas fa-star text-warning"></i>
            @elseif ($avg >= $i - 0.5)
              <i class="fas fa-star-half-alt text-warning"></i>
            @else
              <i class="far fa-star text-warning"></i>
            @endif
          @endfor
          <span class="ms-2 text-muted">({{ $product->reviews->count() }})</span>
        </div>
        @auth
          @if(!$product->reviews->where('user_id', auth()->id())->count())
            <form method="POST" action="{{ route('products.rate', $product) }}" class="mb-4">
              @csrf
              <div class="star-rating mb-2 d-flex flex-row-reverse justify-content-end">
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
      </div>
    </div>
  </div>
  <div class="row mt-5">
    <div class="col-lg-8 mx-auto">
      <div class="card border-0 shadow-sm p-4">
        <h4 class="mb-4">Customer Reviews</h4>
        @forelse($product->reviews as $review)
          <div class="review mb-3 p-3 border rounded bg-light" data-review-id="{{ $review->id }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                <strong>{{ $review->user ? $review->user->name : 'Deleted User' }}</strong>
                <div class="review-rating">
                  @for ($i = 1; $i <= 5; $i++)
                    <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-warning"></i>
                  @endfor
                </div>
              </div>
              @auth
                @if(Auth::id() == $review->user_id)
                  <div class="review-actions">
                    <button class="btn btn-sm btn-outline-gold edit-review-btn"
                      data-review-id="{{ $review->id }}"
                      data-review='@json(["rating" => $review->rating, "comment" => $review->comment])'>
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-review-btn"
                      data-review-id="{{ $review->id }}">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                @endif
              @endauth
            </div>
            <p class="mb-1 review-comment">{{ $review->comment }}</p>
            <small class="text-muted review-time">{{ $review->updated_at->diffForHumans() }}</small>
          </div>
        @empty
          <p class="text-muted">No reviews yet. Be the first to review this product!</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

<!-- Review Edit Modal -->
<div class="modal fade" id="editReviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Your Review</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editReviewForm">
        <div class="modal-body">
          @csrf
          @method('PUT')
          <input type="hidden" name="review_id" id="editReviewId">
          <div class="mb-3">
            <label class="form-label">Rating</label>
            <div class="star-rating" id="editStarRating" style="display: flex; flex-direction: row-reverse; justify-content: flex-end; cursor:pointer;">
              @for ($i = 5; $i >= 1; $i--)
                <i class="fas fa-star edit-modal-star" data-value="{{ $i }}" style="font-size:2rem; color:#ccc; margin:0 2px;"></i>
              @endfor
            </div>
            <input type="hidden" name="rating" id="editRatingValue">
          </div>
          <div class="mb-3">
            <label for="editComment" class="form-label">Comment</label>
            <textarea name="comment" id="editComment" class="form-control" rows="4"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-gold">Update Review</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
