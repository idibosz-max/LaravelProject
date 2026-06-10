@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="flex items-center justify-between mb-10">
        <div class="flex items-center gap-4">
            <div class="w-10 h-0.5 bg-[#DC2626]"></div>
            <h1 class="font-display text-5xl tracking-widest text-white">ADMIN</h1>
        </div>
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-white text-sm transition-colors">← View Store</a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        @foreach([
            ['Total Orders',    $stats['total_orders'],    '📦', 'text-blue-400'],
            ['Revenue',         '$'.number_format($stats['total_revenue'],2), '💰', 'text-green-400'],
            ['Products',        $stats['total_products'],  '🛍️', 'text-purple-400'],
            ['Customers',       $stats['total_customers'], '👥', 'text-yellow-400'],
        ] as [$label, $value, $icon, $color])
        <div class="bg-[#1a1a1a] border border-white/10 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-2xl">{{ $icon }}</span>
                <div class="w-2 h-2 bg-[#DC2626] rounded-full"></div>
            </div>
            <p class="font-display text-3xl {{ $color }} mb-1">{{ $value }}</p>
            <p class="text-gray-500 text-xs uppercase tracking-widest font-medium">{{ $label }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Recent Orders --}}
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-0.5 bg-[#DC2626]"></div>
                    <h2 class="font-display text-2xl tracking-widest text-white">RECENT ORDERS</h2>
                </div>
                <a href="{{ route('admin.orders.index') }}"
                   class="text-[#DC2626] hover:text-red-400 text-xs font-semibold uppercase tracking-wider transition-colors">
                    View All →
                </a>
            </div>

            <div class="bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden">
                @if($recentOrders->isEmpty())
                    <div class="text-center py-12 text-gray-600">
                        <p class="text-sm">No orders yet</p>
                    </div>
                @else
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="px-5 py-4 text-left text-[10px] uppercase tracking-widest text-gray-500 font-semibold">Order</th>
                            <th class="px-5 py-4 text-left text-[10px] uppercase tracking-widest text-gray-500 font-semibold">Customer</th>
                            <th class="px-5 py-4 text-left text-[10px] uppercase tracking-widest text-gray-500 font-semibold">Total</th>
                            <th class="px-5 py-4 text-left text-[10px] uppercase tracking-widest text-gray-500 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($recentOrders as $order)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-5 py-4 text-gray-400 text-sm font-mono">
                                #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-5 py-4 text-white text-sm">{{ $order->user->name }}</td>
                            <td class="px-5 py-4 text-[#DC2626] font-bold text-sm">
                                ${{ number_format($order->total, 2) }}
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $colors = [
                                        'pending'    => 'bg-yellow-900/40 text-yellow-400 border-yellow-700/50',
                                        'processing' => 'bg-purple-900/40 text-purple-400 border-purple-700/50',
                                        'shipped'    => 'bg-blue-900/40 text-blue-400 border-blue-700/50',
                                        'delivered'  => 'bg-green-900/40 text-green-400 border-green-700/50',
                                        'cancelled'  => 'bg-red-900/40 text-red-400 border-red-700/50',
                                    ];
                                    $c = $colors[$order->status] ?? 'bg-gray-800 text-gray-400 border-gray-700';
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $c }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Low Stock --}}
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-6 h-0.5 bg-[#DC2626]"></div>
                    <h2 class="font-display text-2xl tracking-widest text-white">LOW STOCK</h2>
                </div>
                <div class="bg-[#1a1a1a] border border-white/10 rounded-xl p-4 space-y-3">
                    @forelse($lowStock as $p)
                    <div class="flex items-center justify-between">
                        <span class="text-white text-sm font-medium truncate max-w-[160px]">{{ $p->name }}</span>
                        <span class="bg-[#DC2626]/10 border border-[#DC2626]/30 text-[#DC2626]
                                     text-xs font-bold px-2.5 py-1 rounded-full ml-2 shrink-0">
                            {{ $p->stock }} left
                        </span>
                    </div>
                    @empty
                    <div class="flex items-center gap-2 text-green-400 text-sm">
                        <span class="text-lg">✓</span>
                        <span>All products well stocked</span>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Actions --}}
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-6 h-0.5 bg-[#DC2626]"></div>
                    <h2 class="font-display text-2xl tracking-widest text-white">QUICK ACTIONS</h2>
                </div>
                <div class="space-y-2">
                    <a href="{{ route('admin.products.create') }}"
                       class="flex items-center gap-3 bg-[#DC2626] hover:bg-[#991B1B]
                              rounded-lg px-4 py-3 text-sm text-white font-semibold
                              transition-colors duration-200">
                        <span class="text-base">+</span> Add New Product
                    </a>
                    <a href="{{ route('admin.products.index') }}"
                       class="flex items-center gap-3 bg-[#1a1a1a] border border-white/10
                              hover:border-[#DC2626]/40 rounded-lg px-4 py-3 text-sm
                              text-gray-300 hover:text-white transition-all duration-200">
                        <span class="text-[#DC2626]">→</span> Manage Products
                    </a>
                    <a href="{{ route('admin.orders.index') }}"
                       class="flex items-center gap-3 bg-[#1a1a1a] border border-white/10
                              hover:border-[#DC2626]/40 rounded-lg px-4 py-3 text-sm
                              text-gray-300 hover:text-white transition-all duration-200">
                        <span class="text-[#DC2626]">→</span> Manage Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
