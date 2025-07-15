@props(['product'])

<div class="luxury-product group relative overflow-hidden transition-all duration-500 hover:shadow-lg">
    <a href="{{ route('products.show', $product) }}" class="block overflow-hidden">
        <img src="{{ $product->image }}"
             alt="{{ $product->name }}"
             class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
    </a>
    <div class="p-4 text-center">
        <h3 class="font-serif text-lg">{{ $product->name }}</h3>
        <p class="text-gold my-2">${{ number_format($product->price, 2) }}</p>
        <button class="luxury-btn-sm add-to-cart"
                data-product-id="{{ $product->id }}"
                x-data="{ showTooltip: false }"
                @mouseenter="showTooltip = true"
                @mouseleave="showTooltip = false">
            Add to Bag
            <span x-show="showTooltip" x-transition class="absolute bg-black text-white text-xs p-1 rounded mt-1">
                {{ $product->requiredSizes() ? 'Select size' : 'Add to cart' }}
            </span>
        </button>
    </div>
</div>
