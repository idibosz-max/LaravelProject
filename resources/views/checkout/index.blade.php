@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <div class="flex items-center gap-4 mb-10">
        <div class="w-8 h-px bg-dib-red"></div>
        <h1 class="font-display text-5xl tracking-widest">CHECKOUT</h1>
    </div>

    {{-- Step indicator --}}
    <div class="flex items-center gap-0 mb-12">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-dib-red rounded-full flex items-center justify-center text-xs font-bold">1</div>
            <span class="text-sm font-medium">Shipping</span>
        </div>
        <div class="flex-1 h-px bg-white/10 mx-4"></div>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-dib-gray border border-white/10 rounded-full flex items-center justify-center text-xs text-gray-500">2</div>
            <span class="text-sm text-gray-500">Payment</span>
        </div>
        <div class="flex-1 h-px bg-white/10 mx-4"></div>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-dib-gray border border-white/10 rounded-full flex items-center justify-center text-xs text-gray-500">3</div>
            <span class="text-sm text-gray-500">Confirm</span>
        </div>
    </div>

    <form method="POST" action="{{ route('checkout.payment') }}" class="space-y-6">
        @csrf

        <div class="bg-dib-gray border border-white/5 rounded-lg p-6">
            <h2 class="font-display text-xl tracking-widest text-dib-red mb-6">SHIPPING INFORMATION</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Full Name</label>
                    <input type="text" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}"
                           class="w-full bg-dib-dark border border-white/10 rounded px-4 py-3 text-white text-sm focus:border-dib-red focus:outline-none" required>
                    @error('shipping_name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Email</label>
                    <input type="email" name="shipping_email" value="{{ old('shipping_email', auth()->user()->email) }}"
                           class="w-full bg-dib-dark border border-white/10 rounded px-4 py-3 text-white text-sm focus:border-dib-red focus:outline-none" required>
                    @error('shipping_email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Street Address</label>
                    <input type="text" name="shipping_address" value="{{ old('shipping_address') }}"
                           class="w-full bg-dib-dark border border-white/10 rounded px-4 py-3 text-white text-sm focus:border-dib-red focus:outline-none" required>
                    @error('shipping_address') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">City</label>
                    <input type="text" name="shipping_city" value="{{ old('shipping_city') }}"
                           class="w-full bg-dib-dark border border-white/10 rounded px-4 py-3 text-white text-sm focus:border-dib-red focus:outline-none" required>
                    @error('shipping_city') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">ZIP / Postal Code</label>
                    <input type="text" name="shipping_zip" value="{{ old('shipping_zip') }}"
                           class="w-full bg-dib-dark border border-white/10 rounded px-4 py-3 text-white text-sm focus:border-dib-red focus:outline-none" required>
                    @error('shipping_zip') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Country</label>
                    <input type="text" name="shipping_country" value="{{ old('shipping_country') }}"
                           class="w-full bg-dib-dark border border-white/10 rounded px-4 py-3 text-white text-sm focus:border-dib-red focus:outline-none" required>
                    @error('shipping_country') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <button type="submit"
                class="w-full bg-dib-red hover:bg-dib-red-dark text-white font-semibold py-4 rounded uppercase tracking-widest text-sm transition-colors">
            Continue to Payment →
        </button>
    </form>
</div>
@endsection
