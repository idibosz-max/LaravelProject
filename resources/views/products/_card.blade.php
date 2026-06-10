<article class="group bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden hover:border-[#DC2626]/60 transition-all duration-300 flex flex-col">

    {{-- Image --}}
    <a href="{{ route('products.show', $product) }}" class="block relative overflow-hidden bg-[#111111]">
        <div class="aspect-square flex items-center justify-center">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="flex flex-col items-center gap-2 text-[#DC2626]/30">
                    <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="text-xs text-gray-600">No image</span>
                </div>
            @endif
        </div>

        {{-- Badges --}}
        <div class="absolute top-3 left-3 flex flex-col gap-1.5">
            @if($product->is_on_sale)
                <span class="bg-[#DC2626] text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">
                    {{ round((1 - $product->sale_price / $product->price) * 100) }}% OFF
                </span>
            @endif
            @if($product->stock === 0)
                <span class="bg-gray-800 text-gray-400 text-[10px] font-semibold px-2 py-1 rounded uppercase tracking-wider">
                    Sold Out
                </span>
            @elseif($product->stock <= 5)
                <span class="bg-orange-900 text-orange-300 text-[10px] font-semibold px-2 py-1 rounded uppercase tracking-wider">
                    Low Stock
                </span>
            @endif
        </div>
    </a>

    {{-- Info --}}
    <div class="p-5 flex flex-col flex-1">
        <p class="text-[#DC2626] text-[10px] font-semibold uppercase tracking-widest mb-1.5">
            {{ $product->category->name }}
        </p>

        <h3 class="font-semibold text-white text-sm leading-snug mb-3 flex-1 line-clamp-2
                   group-hover:text-[#DC2626] transition-colors duration-200">
            <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
        </h3>

        {{-- Price --}}
        <div class="flex items-center gap-2.5 mb-4">
            @if($product->is_on_sale)
                <span class="text-[#DC2626] font-bold text-lg">
                    ${{ number_format($product->sale_price, 2) }}
                </span>
                <span class="text-gray-500 text-sm line-through">
                    ${{ number_format($product->price, 2) }}
                </span>
            @else
                <span class="text-white font-bold text-lg">
                    ${{ number_format($product->price, 2) }}
                </span>
            @endif
        </div>

        {{-- Add to cart --}}
        @if($product->stock > 0)
            <form method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                        class="w-full bg-[#DC2626] hover:bg-[#991B1B] active:bg-[#7f1d1d]
                               text-white text-xs font-semibold py-3 rounded-lg
                               uppercase tracking-widest transition-colors duration-200">
                    Add to Cart
                </button>
            </form>
        @else
            <button disabled
                    class="w-full bg-[#1f1f1f] border border-gray-700 text-gray-500
                           text-xs font-semibold py-3 rounded-lg uppercase tracking-widest cursor-not-allowed">
                Out of Stock
            </button>
        @endif
    </div>
</article>
