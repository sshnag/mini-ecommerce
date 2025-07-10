@extends('layouts.app')
@section('title','Home')
@push('style')
    <link rel="stylesheet" href="{{asset('css/home.css')}}">
@endpush
@section('content')
    <!-- Hero Section -->
    <section class="luxury-hero bg-cover bg-center h-screen-80 flex items-center">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-serif text-white mb-6">Timeless Elegance</h1>
            <p class="text-lg sm:text-xl text-white mb-8 max-w-2xl mx-auto">Discover our latest collection of handcrafted jewels</p>
            <a href="#featured" class="inline-block bg-gold hover:bg-gold-dark text-black px-6 py-3 sm:px-8 sm:py-4 font-medium transition duration-300">
                View Collection
            </a>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="featured" class="py-12 sm:py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl font-serif text-center mb-12 sm:mb-16">Latest Creations</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-12">
                @foreach($latestjewel as $product)
                    <div class="luxury-product-card group transition duration-300 hover:shadow-lg">
                        <a href="{{ route('products.show', $product) }}" class="block overflow-hidden mb-4">
                            <img src="{{ $product->image_url }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-64 sm:h-80 md:h-96 object-cover transition duration-500 group-hover:scale-105">
                        </a>
                        <div class="text-center px-4 pb-6">
                            <h3 class="font-serif text-lg sm:text-xl mb-2">{{ $product->name }}</h3>
                            <p class="text-gold mb-4">${{ number_format($product->price, 2) }}</p>
                            <button class="luxury-add-to-bag border border-black px-6 py-2 hover:bg-black hover:text-white transition duration-300"
                                    data-product-id="{{ $product->id }}">
                                ADD TO BAG
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Size Modal -->
    <div id="sizeModal" class="luxury-size-modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="modal-content bg-white p-8 max-w-md w-full">
            <h3 class="text-2xl font-serif mb-6">SELECT SIZE</h3>
            <div class="size-options grid grid-cols-4 gap-3 mb-8">
                <!-- Dynamically filled via JavaScript -->
            </div>
            <button class="modal-close border border-black px-6 py-2 hover:bg-black hover:text-white transition duration-300">
                CANCEL
            </button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Product card interaction
    document.querySelectorAll('.luxury-add-to-bag').forEach(button => {
        button.addEventListener('click', (e) => {
            const productId = e.target.dataset.productId;

            if (e.target.dataset.isJewelry === 'true') {
                showSizeModal(productId);
            } else {
                addToCart(productId);
            }
        });
    });

    function showSizeModal(productId) {
        const modal = document.getElementById('sizeModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function addToCart(productId, size = null) {
        // AJAX implementation would go here
        console.log(`Adding product ${productId} with size ${size}`);
    }

    // Close modal when clicking cancel
    document.querySelector('.modal-close').addEventListener('click', () => {
        document.getElementById('sizeModal').classList.add('hidden');
    });
</script>
@endpush
