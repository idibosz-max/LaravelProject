<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DIB Productions') | DIB Productions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="bg-[#0a0a0a] text-white font-body antialiased min-h-screen">

{{-- ── Navigation ───────────────────────────────────────────────────────────── --}}
<nav class="sticky top-0 z-50 bg-[#0a0a0a]/95 backdrop-blur-md border-b border-[#DC2626]/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
                <div class="w-8 h-8 bg-[#DC2626] rounded flex items-center justify-center font-display text-white text-lg leading-none group-hover:bg-[#991B1B] transition-colors">D</div>
                <span class="font-display text-xl tracking-widest text-white">DIB PRODUCTIONS</span>
            </a>

            {{-- Nav Links --}}
            <div class="hidden lg:flex items-center gap-6">
                <a href="{{ route('products.index') }}" class="text-gray-300 hover:text-[#DC2626] transition-colors text-sm font-medium tracking-wide uppercase">Shop</a>
                @foreach(\App\Models\Category::limit(4)->get() as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                       class="text-gray-300 hover:text-[#DC2626] transition-colors text-sm font-medium tracking-wide uppercase">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-4">
                {{-- Cart React mount --}}
                <div id="cart-icon-root"></div>

                {{-- Auth --}}
                @auth
                    <div class="flex items-center gap-3">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="text-xs text-[#DC2626] font-semibold uppercase tracking-wider hover:text-red-400 transition-colors border border-[#DC2626]/40 px-3 py-1.5 rounded">
                                Admin
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-white text-sm transition-colors">Logout</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-white transition-colors">Login</a>
                    <a href="{{ route('register') }}"
                       class="bg-[#DC2626] hover:bg-[#991B1B] text-white text-sm px-4 py-2 rounded font-medium transition-colors">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- ── Flash Messages ───────────────────────────────────────────────────────── --}}
@if(session('success'))
    <div class="bg-green-950 border-b border-green-700 text-green-300 px-6 py-3 text-sm text-center font-medium">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="bg-red-950 border-b border-red-800 text-red-300 px-6 py-3 text-sm text-center font-medium">
        {{ session('error') }}
    </div>
@endif

{{-- ── Page Content ─────────────────────────────────────────────────────────── --}}
<main>@yield('content')</main>

{{-- ── Footer ──────────────────────────────────────────────────────────────── --}}
<footer class="bg-[#111111] border-t border-[#DC2626]/20 mt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="md:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-[#DC2626] rounded flex items-center justify-center font-display text-white text-lg">D</div>
                    <span class="font-display text-xl tracking-widest text-white">DIB PRODUCTIONS</span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Premium electronics, audio, and lifestyle products. Built for those who demand the best.
                </p>
            </div>
            <div>
                <h4 class="font-display tracking-widest text-[#DC2626] mb-4 text-lg">SHOP</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('products.index') }}" class="text-gray-400 hover:text-white transition-colors">All Products</a></li>
                    @foreach(\App\Models\Category::all() as $cat)
                        <li><a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="text-gray-400 hover:text-white transition-colors">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="font-display tracking-widest text-[#DC2626] mb-4 text-lg">ACCOUNT</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors">Login</a></li>
                    <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors">Register</a></li>
                    <li><a href="{{ route('cart.index') }}" class="text-gray-400 hover:text-white transition-colors">Cart</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-[#DC2626]/10 mt-12 pt-8 text-center text-gray-600 text-xs">
            &copy; {{ date('Y') }} DIB Productions. All rights reserved.
        </div>
    </div>
</footer>

</body>
</html>
