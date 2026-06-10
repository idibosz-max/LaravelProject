@extends('layouts.app')
@section('title', 'Payment')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <div class="flex items-center gap-4 mb-10">
        <div class="w-8 h-px bg-dib-red"></div>
        <h1 class="font-display text-5xl tracking-widest">PAYMENT</h1>
    </div>

    <div class="bg-dib-gray border border-white/5 rounded-lg p-6 mb-6">
        <h2 class="font-display text-xl tracking-widest text-dib-red mb-6">ORDER TOTAL: ${{ number_format($total, 2) }}</h2>

        {{-- Stripe Payment Element mounts here (React component) --}}
        <div id="stripe-checkout-root"
             data-total="{{ $total }}"
             data-stripe-key="{{ $stripeKey }}"
             data-action="{{ route('checkout.store') }}"
             data-csrf="{{ csrf_token() }}">
        </div>
    </div>
</div>
@endsection
