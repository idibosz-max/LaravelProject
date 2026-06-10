@extends('layouts.app')
@section('title', 'Shopping Cart')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="flex items-center gap-4 mb-10">
        <div class="w-10 h-0.5 bg-[#DC2626]"></div>
        <h1 class="font-display text-5xl tracking-widest text-white">YOUR CART</h1>
    </div>

    @if(empty($items))
        <div class="text-center py-32 border border-white/5 rounded-xl bg-[#111]">
            <svg class="w-16 h-16 text-gray-700 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <p class="font-display text-4xl tracking-widest text-gray-600 mb-3">CART IS EMPTY</p>
            <p class="text-gray-500 text-sm mb-8">Browse our collection and add something you love.</p>
            <a href="{{ route('products.index') }}"
               class="inline-block bg-[#DC2626] hover:bg-[#991B1B] text-white
                      px-8 py-4 rounded-lg uppercase tracking-widest text-sm font-semibold
                      transition-colors duration-200">
                Start Shopping
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Cart Items --}}
            <div class="lg:col-span-2 space-y-4">
                @foreach($items as $item)
                <div class="flex gap-5 bg-[#1a1a1a] border border-white/10 rounded-xl p-5">
                    {{-- Product image --}}
                    <div class="w-24 h-24 bg-[#111] rounded-lg overflow-hidden shrink-0 flex items-center justify-center">
                        @if($item['product']->image)
                            <img src="{{ asset('storage/' . $item['product']->image) }}"
                                 alt="{{ $item['product']->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-8 h-8 bg-[#DC2626]/10 rounded"></div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-[#DC2626] text-[10px] font-semibold uppercase tracking-widest mb-1">
                            {{ $item['product']->category->name }}
                        </p>
                        <h3 class="font-semibold text-white text-sm leading-snug mb-1 truncate">
                            {{ $item['product']->name }}
                        </h3>
                        <p class="text-[#DC2626] font-bold text-base">
                            ${{ number_format($item['product']->effective_price, 2) }}
                        </p>
                    </div>

                    {{-- Controls --}}
                    <div class="flex flex-col items-end justify-between shrink-0">
                        <form method="POST" action="{{ route('cart.remove', $item['product']->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-gray-600 hover:text-[#DC2626] transition-colors text-xs font-medium">
                                ✕ Remove
                            </button>
                        </form>

                        {{-- Qty --}}
                        <div class="flex items-center gap-1">
                            <form method="POST" action="{{ route('cart.update', $item['product']->id) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ max(0, $item['quantity'] - 1) }}">
                                <button type="submit"
                                        class="w-8 h-8 bg-[#111] border border-white/15 rounded-lg
                                               text-white hover:border-[#DC2626]/50 transition-colors
                                               text-sm font-bold flex items-center justify-center">−</button>
                            </form>
                            <span class="text-white text-sm font-semibold w-8 text-center">{{ $item['quantity'] }}</span>
                            <form method="POST" action="{{ route('cart.update', $item['product']->id) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                <button type="submit"
                                        class="w-8 h-8 bg-[#111] border border-white/15 rounded-lg
                                               text-white hover:border-[#DC2626]/50 transition-colors
                                               text-sm font-bold flex items-center justify-center">+</button>
                            </form>
                        </div>

                        <p class="text-white font-bold text-sm">
                            ${{ number_format($item['subtotal'], 2) }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-[#1a1a1a] border border-white/10 rounded-xl p-6 sticky top-24">
                    <h2 class="font-display text-2xl tracking-widest text-white mb-6">ORDER SUMMARY</h2>

                    <div class="space-y-3 text-sm mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Subtotal</span>
                            <span class="text-white font-medium">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Shipping</span>
                            <span class="{{ $total >= 100 ? 'text-green-400 font-semibold' : 'text-white font-medium' }}">
                                {{ $total >= 100 ? 'FREE' : '$9.99' }}
                            </span>
                        </div>
                        @if($total >= 100)
                        <div class="text-xs text-green-400 bg-green-950/50 border border-green-800/30 rounded-lg px-3 py-2">
                            🎉 You qualify for free shipping!
                        </div>
                        @else
                        <div class="text-xs text-gray-500 bg-[#111] rounded-lg px-3 py-2">
                            Add ${{ number_format(100 - $total, 2) }} more for free shipping
                        </div>
                        @endif
                        <div class="border-t border-white/10 pt-3 flex justify-between font-bold">
                            <span class="text-white text-base">Total</span>
                            <span class="text-[#DC2626] text-xl">
                                ${{ number_format($total >= 100 ? $total : $total + 9.99, 2) }}
                            </span>
                        </div>
                    </div>

                    @auth
                        <a href="{{ route('checkout.index') }}"
                           class="block w-full text-center bg-[#DC2626] hover:bg-[#991B1B]
                                  text-white font-semibold py-4 rounded-lg uppercase tracking-widest
                                  text-sm transition-colors duration-200">
                            Proceed to Checkout
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="block w-full text-center bg-[#DC2626] hover:bg-[#991B1B]
                                  text-white font-semibold py-4 rounded-lg uppercase tracking-widest
                                  text-sm transition-colors duration-200">
                            Login to Checkout
                        </a>
                        <p class="text-center text-gray-500 text-xs mt-2">You need to be logged in to checkout</p>
                    @endauth

                    <a href="{{ route('products.index') }}"
                       class="block text-center text-gray-500 hover:text-gray-300 text-xs mt-4 transition-colors">
                        ← Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
