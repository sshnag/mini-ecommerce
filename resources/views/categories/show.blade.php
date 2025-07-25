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
                    <div class="col-md-4 mb-4">
                        <div class="product-card h-100">
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
document.querySelectorAll('.star-rating').forEach(function(ratingDiv) {
    const stars = ratingDiv.querySelectorAll('label');
    stars.forEach(function(star, idx) {
        star.addEventListener('mouseenter', function() {
            for (let i = 0; i <= idx; i++) stars[i].style.color = '#ffc107';
        });
        star.addEventListener('mouseleave', function() {
            stars.forEach(s => s.style.color = '');
        });
    });
    ratingDiv.addEventListener('mouseleave', function() {
        const checked = ratingDiv.querySelector('input[type=radio]:checked');
        if (checked) {
            let idx = Array.from(ratingDiv.querySelectorAll('input[type=radio]')).indexOf(checked);
            for (let i = 0; i < stars.length; i++)
                stars[i].style.color = i <= idx ? '#ffc107' : '';
        }
    });
});
</script>
@endpush
