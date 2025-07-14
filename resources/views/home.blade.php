@extends('layouts.app')

@section('title', 'TIFFANY - Luxury Jewelry')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
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
        <p class="animate__animated animate__fadeIn animate__delay-1s">Book a private viewing of our collections</p>
        <a href="" class="btn-cta animate__animated animate__fadeInUp animate__delay-2s">
            Contact Us <i class="fas fa-chevron-down ms-2"></i>
        </a>
    </div>
</section>
@endsection
