@extends('layouts.app')
@section('title', 'Shop All Products')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-10">
        <div class="w-10 h-0.5 bg-[#DC2626]"></div>
        <h1 class="font-display text-5xl tracking-widest text-white">ALL PRODUCTS</h1>
    </div>

    <div class="flex gap-8">

        {{-- ── Sidebar Filters ──────────────────────────────────────────────── --}}
        <aside class="hidden lg:block w-60 shrink-0">
            <form method="GET" action="{{ route('products.index') }}" class="space-y-8">

                <div>
                    <h3 class="font-display text-base tracking-widest text-[#DC2626] mb-3">SEARCH</h3>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search products…"
                           class="w-full bg-[#1a1a1a] border border-white/15 rounded-lg px-4 py-2.5
                                  text-sm text-white placeholder-gray-500
                                  focus:border-[#DC2626] focus:outline-none transition-colors">
                </div>

                <div>
                    <h3 class="font-display text-base tracking-widest text-[#DC2626] mb-3">CATEGORY</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2.5 text-sm text-gray-300 cursor-pointer hover:text-white transition-colors">
                            <input type="radio" name="category" value=""
                                   {{ !request('category') ? 'checked' : '' }}
                                   class="accent-[#DC2626] w-4 h-4">
                            All Categories
                        </label>
                        @foreach($categories as $cat)
                        <label class="flex items-center gap-2.5 text-sm text-gray-300 cursor-pointer hover:text-white transition-colors">
                            <input type="radio" name="category" value="{{ $cat->slug }}"
                                   {{ request('category') === $cat->slug ? 'checked' : '' }}
                                   class="accent-[#DC2626] w-4 h-4">
                            {{ $cat->name }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="font-display text-base tracking-widest text-[#DC2626] mb-3">PRICE RANGE</h3>
                    <div class="flex gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                               class="w-full bg-[#1a1a1a] border border-white/15 rounded-lg px-3 py-2.5
                                      text-sm text-white placeholder-gray-500
                                      focus:border-[#DC2626] focus:outline-none transition-colors">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                               class="w-full bg-[#1a1a1a] border border-white/15 rounded-lg px-3 py-2.5
                                      text-sm text-white placeholder-gray-500
                                      focus:border-[#DC2626] focus:outline-none transition-colors">
                    </div>
                </div>

                <div>
                    <h3 class="font-display text-base tracking-widest text-[#DC2626] mb-3">SORT BY</h3>
                    <select name="sort"
                            class="w-full bg-[#1a1a1a] border border-white/15 rounded-lg px-4 py-2.5
                                   text-sm text-white focus:border-[#DC2626] focus:outline-none transition-colors">
                        <option value="newest"    {{ request('sort') === 'newest'    ? 'selected' : '' }}>Newest First</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc"{{ request('sort') === 'price_desc'? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name"      {{ request('sort') === 'name'      ? 'selected' : '' }}>Name A–Z</option>
                    </select>
                </div>

                <button type="submit"
                        class="w-full bg-[#DC2626] hover:bg-[#991B1B] text-white py-3 rounded-lg
                               font-semibold text-sm uppercase tracking-widest transition-colors duration-200">
                    Apply Filters
                </button>

                @if(request()->hasAny(['search','category','min_price','max_price','sort']))
                    <a href="{{ route('products.index') }}"
                       class="block text-center text-gray-500 text-xs hover:text-gray-300 transition-colors">
                        ✕ Clear all filters
                    </a>
                @endif
            </form>
        </aside>

        {{-- ── Product Grid ─────────────────────────────────────────────────── --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between mb-6">
                <p class="text-gray-400 text-sm">
                    <span class="text-white font-semibold">{{ $products->total() }}</span> products found
                </p>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-32 border border-white/5 rounded-xl bg-[#111]">
                    <p class="font-display text-5xl tracking-widest text-gray-700 mb-4">NO PRODUCTS FOUND</p>
                    <p class="text-gray-500 text-sm mb-6">Try adjusting your filters or search terms.</p>
                    <a href="{{ route('products.index') }}"
                       class="inline-block text-[#DC2626] hover:text-red-400 text-sm font-medium transition-colors">
                        ← Back to all products
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        @include('products._card', ['product' => $product])
                    @endforeach
                </div>
                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
