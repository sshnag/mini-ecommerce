@extends('layouts.app')

@section('title', $product->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

      <form action="{{ route('cart.store') }}" method="POST" class="d-flex align-items-center gap-2 mt-4">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="quantity-control d-flex align-items-center">
          <button type="button" id="decreaseQty" class="btn btn-outline-secondary">-</button>
          <input type="text" name="quantity" id="quantityInput" value="1" readonly class="form-control text-center mx-2" style="width: 60px;">
          <button type="button" id="increaseQty" class="btn btn-outline-secondary">+</button>
        </div>

        <button type="submit" class="btn btn-main">
          <i class="fas fa-shopping-cart me-1"></i> Add to Bag
        </button>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const qtyInput = document.getElementById('quantityInput');

    decreaseBtn.addEventListener('click', () => {
      let current = parseInt(qtyInput.value);
      if (current > 1) {
        qtyInput.value = current - 1;
      }
    });

    increaseBtn.addEventListener('click', () => {
      let current = parseInt(qtyInput.value);
      qtyInput.value = current + 1;
    });
  });
</script>
@endpush
