@extends('layouts.app')
@section('title', 'Order Confirmed')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-24 text-center">
    <div class="w-20 h-20 bg-dib-red/10 border border-dib-red/30 rounded-full flex items-center justify-center mx-auto mb-8">
        <svg class="w-10 h-10 text-dib-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h1 class="font-display text-6xl tracking-widest text-white mb-4">ORDER CONFIRMED</h1>
    <p class="text-gray-400 mb-2">Thank you, {{ $order->shipping_name }}!</p>
    <p class="text-gray-500 text-sm mb-8">A confirmation email has been sent to <span class="text-gray-300">{{ $order->shipping_email }}</span></p>

    <div class="bg-dib-gray border border-white/5 rounded-lg p-6 text-left mb-8">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Order Number</p>
                <p class="text-white font-semibold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Order Total</p>
                <p class="text-dib-red font-bold">${{ number_format($order->total, 2) }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Status</p>
                <span class="inline-block bg-yellow-900/30 border border-yellow-500/30 text-yellow-400 text-xs px-2 py-1 rounded uppercase">{{ $order->status }}</span>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Shipping To</p>
                <p class="text-white text-xs">{{ $order->shipping_city }}, {{ $order->shipping_country }}</p>
            </div>
        </div>
    </div>

    <div class="flex gap-4 justify-center">
        <a href="{{ route('products.index') }}"
           class="bg-dib-red hover:bg-dib-red-dark text-white font-semibold px-8 py-4 rounded uppercase tracking-widest text-sm transition-colors">
            Continue Shopping
        </a>
    </div>
</div>
@endsection
