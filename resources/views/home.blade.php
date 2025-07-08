
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiffany Jewels | Home</title>
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="luxury-home">
    <!-- Navigation -->
    <nav class="luxury-nav">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-serif text-gold">TIFFANY</a>
                <div class="flex space-x-8">
                    <a href="#" class="hover:text-gold transition">Collections</a>
                    <a href="#" class="hover:text-gold transition">Rings</a>
                    <a href="#" class="hover:text-gold transition">Bracelets</a>
                    {{-- <a href="{{ route('login') }}" class="hover:text-gold transition">Account</a> --}}
                    <a href="{{ route('cart.index') }}" class="hover:text-gold transition">Bag (0)</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="luxury-hero">
        <div class="container mx-auto px-6 py-32 text-center">
            <h1 class="text-5xl font-serif text-black mb-6">Timeless Elegance</h1>
            <p class="text-xl text-white mb-8">Discover our latest collection of handcrafted jewels</p>
            <a href="#featured" class="bg-gold hover:bg-gold-dark text-black px-8 py-3 font-medium transition">View Collection</a>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="featured" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-serif text-center mb-16">Latest Creations</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-12">
    @foreach($latestjewel as $product)
        <div class="luxury-product-card group">
            <a href="{{ route('products.show', $product) }}" class="block overflow-hidden mb-4">
                <img
                    src="{{ $product->image_url }}"
                    alt="{{ $product->name }}"
                    class="w-full h-96 object-cover transition duration-500 group-hover:scale-105"
                >
            </a>
            <div class="text-center">
                <h3 class="font-serif text-xl">{{ $product->name }}</h3>
                <p class="text-gold">${{ number_format($product->price, 2) }}</p>
                <button class="luxury-add-to-bag" data-product-id="{{ $product->id }}">
                    ADD TO BAG
                </button>
            </div>
        </div>
    @endforeach
</div>
        </div>
    </section>

    <!-- Size Modal (Hidden by default) -->
    <div id="sizeModal" class="luxury-size-modal">
        <div class="modal-content">
            <h3 class="text-2xl font-serif mb-6">SELECT SIZE</h3>
            <div class="size-options grid grid-cols-4 gap-3 mb-8">
                <!-- Dynamically filled via JavaScript -->
            </div>
            <button class="modal-close">CANCEL</button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="luxury-footer py-12 bg-black text-white">
        <div class="container mx-auto px-6 text-center">
            <p class="text-gold text-xl font-serif mb-6">TIFFANY</p>
            <p>© {{ date('Y') }} All Rights Reserved</p>
        </div>
    </footer>

    <script>
        // Product card interaction
        document.querySelectorAll('.luxury-add-to-bag').forEach(button => {
            button.addEventListener('click', (e) => {
                const productId = e.target.dataset.productId;

                // Check if jewelry (would come from data attribute)
                if (e.target.dataset.isJewelry === 'true') {
                    showSizeModal(productId);
                } else {
                    addToCart(productId);
                }
            });
        });

        function showSizeModal(productId) {
            // Fetch sizes via AJAX or use data attributes
            const modal = document.getElementById('sizeModal');
            modal.style.display = 'flex';
        }

        function addToCart(productId, size = null) {
            // AJAX call to cart endpoint
            console.log(`Adding product ${productId} with size ${size}`);
            // Show temporary cart dropdown
        }
    </script>
</body>
</html>
