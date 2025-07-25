@extends('layouts.app')

@section('title', 'TIFFANY - Luxury Jewelry')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<!-- Cookie Consent Popup -->
<div id="cookieConsent" style="position:fixed;bottom:20px;left:0;right:0;z-index:9999;display:none;">
    <div style="max-width:500px;margin:0 auto;background:#222;color:#fff;padding:20px 30px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.2);display:flex;align-items:center;justify-content:space-between;">
        <span>We use cookies to ensure you get the best experience on our website.</span>
        <button id="acceptCookies" style="background:#bfa36f;color:#fff;border:none;padding:8px 18px;border-radius:5px;cursor:pointer;font-weight:500;">Accept</button>
    </div>
</div>
<!-- Hero Section -->
<section class="tiffany-hero">
    <div class="hero-content">
        <h1 class="hero-title animate__animated animate__fadeIn">TIFFANY</h1>
        <p class="hero-subtitle animate__animated animate__fadeIn animate__delay-1s">Timeless elegance since 1837</p>
    </div>
</section>

<!-- Collections Section -->
<section class="tiffany-collections">
    <!-- Rings -->
    <div class="collection-item">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 order-md-1 order-2">
                    <div class="collection-content">
                        <h2 class="animate__animated animate__fadeInLeft">Rings</h2>
                        <p class="animate__animated animate__fadeInLeft animate__delay-1s">Our diamond rings showcase exceptional craftsmanship with ethically sourced stones. Each piece reflects Tiffany's commitment to quality and timeless design.</p>
                        <a href="{{ route('categories.show', 'rings') }}" class="btn-collection animate__animated animate__fadeInUp animate__delay-2s">
                            Explore Collection <i class="fas fa-chevron-right ms-2"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 order-md-2 order-1">
                    <a href="{{ route('categories.show', 'rings') }}" class="collection-image animate__animated animate__fadeIn">
                        <img src="{{ asset('images/rings.jpg') }}" alt="Tiffany Rings" class="img-fluid">
                        <div class="image-overlay"></div>
                    </a>
                </div>
            </div>
        </div>
    </div>
<!-- Limited Edition Section -->
<section class="limited-edition-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title animate__animated animate__fadeInDown">Limited Edition</h2>
            <p class="text-muted">Discover exclusive pieces with only a few left in stock</p>
        </div>
        <div class="row">
            @foreach ($limitedEditionProducts as $index => $product)
                <div class="col-md-3 mb-4">
                    <div class="card product-card shadow border-0 h-100 animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.2 }}s;">
                        <a href="{{ route('products.show', $product) }}">
<div class="product-image-wrapper">
    <img src="{{ asset('storage/' . $product->image) }}" class="product-image" alt="{{ $product->name }}">
</div>

                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="text-muted mb-1">${{ number_format($product->price, 2) }}</p>
                        </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>



    <!-- Bracelets -->
    <div class="collection-item bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <a href="{{ route('categories.show', 'bracelets') }}" class="collection-image animate__animated animate__fadeIn">
                        <img src="{{ asset('images/bracelets.jpg') }}" alt="Tiffany Bracelets" class="img-fluid">
                        <div class="image-overlay"></div>
                    </a>
                </div>
                <div class="col-md-6">
                    <div class="collection-content">
                        <h2 class="animate__animated animate__fadeInRight">Bracelets</h2>
                        <p class="animate__animated animate__fadeInRight animate__delay-1s">From delicate chains to bold statement pieces, our bracelets are designed to complement every style and occasion with unparalleled elegance.</p>
                        <a href="{{ route('categories.show', 'bracelets') }}" class="btn-collection animate__animated animate__fadeInUp animate__delay-2s">
                            Explore Collection <i class="fas fa-chevron-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Necklaces -->
    <div class="collection-item">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 order-md-1 order-2">
                    <div class="collection-content">
                        <h2 class="animate__animated animate__fadeInLeft">Necklaces</h2>
                        <p class="animate__animated animate__fadeInLeft animate__delay-1s">Discover our exquisite necklaces, each crafted to perfection with the finest materials and attention to detail that defines Tiffany craftsmanship.</p>
                        <a href="{{ route('categories.show', 'necklaces') }}" class="btn-collection animate__animated animate__fadeInUp animate__delay-2s">
                            Explore Collection <i class="fas fa-chevron-right ms-2"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 order-md-2 order-1">
                    <a href="{{ route('categories.show', 'necklaces') }}" class="collection-image animate__animated animate__fadeIn">
                        <img src="{{ asset('images/necklaces.jpg') }}" alt="Tiffany Necklaces" class="img-fluid">
                        <div class="image-overlay"></div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="tiffany-cta">
    <div class="container text-center">
        <h2 class="animate__animated animate__fadeIn">Experience Perfection</h2>
        <p class="animate__animated animate__fadeIn animate__delay-1s">Contact Us for the services</p>
        <label class="btn-cta animate__animated animate__fadeInUp animate__delay-2s">
            <a href="{{route('contact.form')}}" class="text-white text-decoration-none">
            Contact Us
            </a>
        </label>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Cookie Consent Popup
if (!localStorage.getItem('cookieConsent')) {
    document.getElementById('cookieConsent').style.display = 'block';
}
document.getElementById('acceptCookies').onclick = function() {
    localStorage.setItem('cookieConsent', 'true');
    document.getElementById('cookieConsent').style.display = 'none';
};
</script>
@endpush
