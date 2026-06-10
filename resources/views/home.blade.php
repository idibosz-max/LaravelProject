@extends('layouts.app')
@section('title', 'Home')

@section('content')

{{-- ── Hero ──────────────────────────────────────────────────────────────── --}}
<section class="relative min-h-[90vh] flex items-center overflow-hidden">
    {{-- Dark grid background --}}
    <div class="absolute inset-0 bg-[#0a0a0a]">
        <div class="absolute inset-0 opacity-[0.04]"
             style="background-image: repeating-linear-gradient(0deg,transparent,transparent 59px,#DC2626 59px,#DC2626 60px),
                                      repeating-linear-gradient(90deg,transparent,transparent 59px,#DC2626 59px,#DC2626 60px);">
        </div>
    </div>
    {{-- Red glow --}}
    <div class="absolute top-1/3 left-1/4 w-96 h-96 bg-[#DC2626]/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 w-full">
        <div class="max-w-3xl">
            {{-- Live badge --}}
            <div class="inline-flex items-center gap-2 bg-[#DC2626]/10 border border-[#DC2626]/30 rounded-full px-4 py-2 mb-8">
                <div class="w-2 h-2 bg-[#DC2626] rounded-full animate-pulse"></div>
                <span class="text-[#DC2626] text-xs font-semibold uppercase tracking-widest">New Collection Live</span>
            </div>

            <h1 class="font-display leading-none tracking-widest mb-6 text-white">
                <span class="text-[80px] md:text-[110px] block">DIB</span>
                <span class="text-[80px] md:text-[110px] block text-[#DC2626]">PRODUC</span>
                <span class="text-[80px] md:text-[110px] block">TIONS</span>
            </h1>

            <p class="text-gray-400 text-lg md:text-xl max-w-lg mb-10 leading-relaxed">
                Premium electronics, audio gear, and lifestyle products. Engineered for excellence.
            </p>

            <div class="flex items-center gap-4 flex-wrap">
                <a href="{{ route('products.index') }}"
                   class="bg-[#DC2626] hover:bg-[#991B1B] text-white font-semibold px-8 py-4
                          rounded-lg transition-all duration-200 uppercase tracking-widest text-sm">
                    Shop Now
                </a>
                <a href="{{ route('products.index', ['category' => 'audio']) }}"
                   class="border border-[#DC2626]/50 text-[#DC2626] hover:bg-[#DC2626]/10
                          px-8 py-4 rounded-lg transition-all duration-200 uppercase tracking-widest text-sm font-semibold">
                    View Audio
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ── Categories ──────────────────────────────────────────────────────────── --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="flex items-center gap-4 mb-12">
        <div class="w-10 h-0.5 bg-[#DC2626]"></div>
        <h2 class="font-display text-4xl tracking-widest text-white">SHOP BY CATEGORY</h2>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($categories as $cat)
        <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
           class="group relative bg-[#1a1a1a] border border-white/10 rounded-xl p-8
                  hover:border-[#DC2626]/50 hover:bg-[#1f1414] transition-all duration-300 overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-[#DC2626] scale-x-0
                        group-hover:scale-x-100 transition-transform duration-300 origin-left rounded-b-xl"></div>
            <div class="w-10 h-10 bg-[#DC2626]/10 rounded-lg mb-4 flex items-center justify-center">
                <div class="w-4 h-4 bg-[#DC2626] rounded-sm"></div>
            </div>
            <h3 class="font-display text-xl tracking-widest text-white group-hover:text-[#DC2626] transition-colors">
                {{ strtoupper($cat->name) }}
            </h3>
            <p class="text-gray-500 text-xs mt-2 font-medium">
                {{ $cat->products()->active()->count() }} products
            </p>
        </a>
        @endforeach
    </div>
</section>

{{-- ── Featured Products (React mounts here) ──────────────────────────────── --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="flex items-center gap-4 mb-12">
        <div class="w-10 h-0.5 bg-[#DC2626]"></div>
        <h2 class="font-display text-4xl tracking-widest text-white">FEATURED PRODUCTS</h2>
    </div>

    <div id="product-filter-root"
         data-initial="{{ $featured->toJson() }}"
         data-categories="{{ $categories->toJson() }}">
        {{-- Fallback grid while React loads --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($featured as $product)
                @include('products._card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>

{{-- ── Promo Banner ─────────────────────────────────────────────────────────── --}}
<section class="bg-[#DC2626] py-20 mt-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-display text-6xl md:text-8xl tracking-widest text-white mb-4">FREE SHIPPING</h2>
        <p class="text-red-100 text-lg mb-8 font-medium">On all orders over $100 &nbsp;·&nbsp; Worldwide delivery</p>
        <a href="{{ route('products.index') }}"
           class="inline-block bg-white text-[#DC2626] font-bold px-10 py-4 rounded-lg
                  uppercase tracking-widest text-sm hover:bg-gray-100 transition-colors duration-200">
            Shop the Collection
        </a>
    </div>
</section>

@endsection
