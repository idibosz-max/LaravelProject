@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-gray-500 mb-10 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
        <span class="text-gray-700">/</span>
        <a href="{{ route('products.index') }}" class="hover:text-white transition-colors">Shop</a>
        <span class="text-gray-700">/</span>
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
           class="hover:text-white transition-colors">{{ $product->category->name }}</a>
        <span class="text-gray-700">/</span>
        <span class="text-gray-300">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">

        {{-- Image --}}
        <div class="bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden
                    aspect-square flex items-center justify-center">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
            @else
                <div class="flex flex-col items-center gap-3 text-[#DC2626]/20">
                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="text-gray-600 text-sm">No image available</span>
                </div>
            @endif
        </div>

        {{-- Details --}}
        <div class="flex flex-col">
            <p class="text-[#DC2626] text-xs uppercase tracking-widest font-semibold mb-3">
                {{ $product->category->name }}
            </p>

            <h1 class="font-display text-4xl md:text-5xl tracking-widest text-white mb-6 leading-tight">
                {{ strtoupper($product->name) }}
            </h1>

            {{-- Price --}}
            <div class="flex items-baseline gap-4 mb-6">
                @if($product->is_on_sale)
                    <span class="font-display text-4xl text-[#DC2626]">
                        ${{ number_format($product->sale_price, 2) }}
                    </span>
                    <span class="text-gray-500 text-xl line-through">
                        ${{ number_format($product->price, 2) }}
                    </span>
                    <span class="bg-[#DC2626]/10 border border-[#DC2626]/30 text-[#DC2626]
                                 text-xs font-bold px-2.5 py-1.5 rounded uppercase tracking-wider">
                        {{ round((1 - $product->sale_price / $product->price) * 100) }}% OFF
                    </span>
                @else
                    <span class="font-display text-4xl text-white">
                        ${{ number_format($product->price, 2) }}
                    </span>
                @endif
            </div>

            {{-- Description --}}
            <p class="text-gray-400 leading-relaxed mb-6 text-sm">{{ $product->description }}</p>

            {{-- Stock status --}}
            <div class="flex items-center gap-2 mb-6 p-3 bg-[#1a1a1a] rounded-lg border border-white/5">
                @if($product->stock > 10)
                    <div class="w-2.5 h-2.5 bg-green-500 rounded-full shrink-0"></div>
                    <span class="text-green-400 text-sm font-medium">
                        In Stock <span class="text-gray-500 font-normal">({{ $product->stock }} available)</span>
                    </span>
                @elseif($product->stock > 0)
                    <div class="w-2.5 h-2.5 bg-orange-400 rounded-full shrink-0"></div>
                    <span class="text-orange-400 text-sm font-medium">
                        Low Stock — Only {{ $product->stock }} left
                    </span>
                @else
                    <div class="w-2.5 h-2.5 bg-red-500 rounded-full shrink-0"></div>
                    <span class="text-red-400 text-sm font-medium">Out of Stock</span>
                @endif
            </div>

            {{-- Add to Cart --}}
            @if($product->stock > 0)
                <form method="POST" action="{{ route('cart.add') }}" class="flex gap-3 mb-8">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Qty stepper --}}
                    <div class="flex items-center bg-[#1a1a1a] border border-white/15 rounded-lg overflow-hidden">
                        <button type="button"
                                onclick="const i=this.nextElementSibling; i.value=Math.max(1,+i.value-1)"
                                class="w-11 h-12 text-gray-400 hover:text-white hover:bg-white/10
                                       transition-colors text-lg font-bold">−</button>
                        <input type="number" name="quantity" value="1"
                               min="1" max="{{ $product->stock }}"
                               class="w-14 h-12 text-center bg-transparent text-white
                                      text-sm font-semibold focus:outline-none border-x border-white/10">
                        <button type="button"
                                onclick="const i=this.previousElementSibling; i.value=Math.min({{ $product->stock }},+i.value+1)"
                                class="w-11 h-12 text-gray-400 hover:text-white hover:bg-white/10
                                       transition-colors text-lg font-bold">+</button>
                    </div>

                    <button type="submit"
                            class="flex-1 bg-[#DC2626] hover:bg-[#991B1B] text-white font-semibold
                                   py-3 rounded-lg uppercase tracking-widest text-sm transition-colors duration-200">
                        Add to Cart
                    </button>
                </form>
            @else
                <button disabled
                        class="w-full bg-[#1f1f1f] border border-gray-700 text-gray-500
                               font-semibold py-4 rounded-lg uppercase tracking-widest text-sm
                               cursor-not-allowed mb-8">
                    Out of Stock
                </button>
            @endif

            {{-- Product meta --}}
            <div class="border-t border-white/10 pt-6 space-y-3 text-sm">
                <div class="flex items-center gap-3">
                    <span class="text-gray-500 w-24">SKU</span>
                    <span class="text-gray-300 font-mono">DIB-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-gray-500 w-24">Category</span>
                    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                       class="text-[#DC2626] hover:text-red-400 transition-colors">
                        {{ $product->category->name }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($related->isNotEmpty())
    <div class="mt-24">
        <div class="flex items-center gap-4 mb-10">
            <div class="w-10 h-0.5 bg-[#DC2626]"></div>
            <h2 class="font-display text-3xl tracking-widest text-white">YOU MAY ALSO LIKE</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($related as $prod)
                @include('products._card', ['product' => $prod])
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
