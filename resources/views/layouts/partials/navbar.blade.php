@php
    $user      = auth()->user();
    $cartCount = collect(session('cart', []))->sum();
    $link      = fn (bool $active) => $active ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-100';
@endphp

<header x-data="{ mobile: false }" class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/85 backdrop-blur">
    <div class="mx-auto flex h-16 max-w-7xl items-center gap-4 px-4 sm:px-6 lg:px-8">

        <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-extrabold tracking-tight">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-fuchsia-500 text-white shadow-lg shadow-brand-500/30">🛍️</span>
            Lapak
        </a>

        {{-- Pencarian --}}
        <form action="{{ route('products.index') }}" method="GET" class="relative hidden max-w-md flex-1 md:block">
            <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            <input type="search" name="q" value="{{ request()->routeIs('products.index') ? request('q') : '' }}" placeholder="Cari laptop, sepatu, kopi…"
                   class="w-full rounded-full border border-slate-200 bg-slate-100/70 py-2 pl-10 pr-4 text-sm outline-none transition focus:border-brand-400 focus:bg-white focus:ring-4 focus:ring-brand-100">
        </form>

        <nav class="ml-2 hidden items-center gap-1 text-sm font-semibold lg:flex">
            <a href="{{ route('products.index') }}" class="rounded-lg px-3 py-2 {{ $link(request()->routeIs('products.index', 'products.show')) }}">Katalog</a>
            @auth
                <a href="{{ route('products.manage') }}" class="rounded-lg px-3 py-2 {{ $link(request()->routeIs('products.manage', 'products.create', 'products.edit')) }}">Jual</a>
                @can('access-admin')
                    <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-3 py-2 {{ $link(request()->routeIs('admin.*')) }}">Admin</a>
                @endcan
            @endauth
        </nav>

        <div class="ml-auto flex items-center gap-2">
            @auth
                <a href="{{ route('cart.index') }}" class="relative grid h-10 w-10 place-items-center rounded-full border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50" aria-label="Keranjang">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                    @if ($cartCount > 0)
                        <span class="absolute -right-1 -top-1 grid h-5 min-w-5 place-items-center rounded-full bg-rose-500 px-1 text-[11px] font-bold text-white">{{ $cartCount }}</span>
                    @endif
                </a>

                {{-- Menu akun --}}
                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
                    <button @click="open = !open" class="flex items-center gap-2 rounded-full border border-slate-200 bg-white py-1 pl-1 pr-3 transition hover:bg-slate-50">
                        <span class="grid h-8 w-8 place-items-center rounded-full bg-gradient-to-br from-brand-500 to-fuchsia-500 text-xs font-bold text-white">{{ $user->initials }}</span>
                        <span class="hidden text-sm font-semibold sm:block">{{ \Illuminate\Support\Str::words($user->name, 1, '') }}</span>
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>

                    <div x-show="open" x-cloak x-transition.origin.top.right class="absolute right-0 mt-2 w-64 rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl shadow-slate-300/50">
                        <div class="px-3 py-2.5">
                            <p class="truncate font-semibold">{{ $user->name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $user->email }}</p>
                            <x-badge :type="$user->role_badge" class="mt-2">{{ $user->role_label }}</x-badge>
                        </div>
                        <div class="my-1 border-t border-slate-100"></div>
                        @php $item = 'flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100'; @endphp
                        <a href="{{ route('dashboard') }}" class="{{ $item }}">🏠 Dashboard</a>
                        <a href="{{ route('orders.index') }}" class="{{ $item }}">🧾 Pesanan Saya</a>
                        <a href="{{ route('products.manage') }}" class="{{ $item }}">📦 Kelola Produk</a>
                        @can('access-admin')
                            <a href="{{ route('admin.dashboard') }}" class="{{ $item }}">🛡️ Panel Admin</a>
                        @endcan
                        <a href="{{ route('profile.edit') }}" class="{{ $item }}">⚙️ Profil</a>
                        <div class="my-1 border-t border-slate-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm font-medium text-rose-600 hover:bg-rose-50">↩️ Keluar</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="hidden rounded-full px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 sm:block">Masuk</a>
                <a href="{{ route('register') }}" class="rounded-full bg-gradient-to-r from-brand-600 to-fuchsia-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-brand-500/30 transition hover:opacity-90">Daftar</a>
            @endauth

            <button @click="mobile = !mobile" class="grid h-10 w-10 place-items-center rounded-full border border-slate-200 lg:hidden" aria-label="Menu">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>
        </div>
    </div>

    {{-- Menu mobile --}}
    <div x-show="mobile" x-cloak x-transition class="border-t border-slate-100 bg-white px-4 py-3 lg:hidden">
        <form action="{{ route('products.index') }}" method="GET" class="mb-3">
            <input type="search" name="q" placeholder="Cari produk…" class="w-full rounded-full border border-slate-200 bg-slate-100/70 px-4 py-2 text-sm outline-none focus:border-brand-400">
        </form>
        <div class="grid gap-1 text-sm font-semibold">
            <a href="{{ route('products.index') }}" class="rounded-lg px-3 py-2 hover:bg-slate-100">Katalog</a>
            @auth
                <a href="{{ route('products.manage') }}" class="rounded-lg px-3 py-2 hover:bg-slate-100">Jual</a>
                @can('access-admin')
                    <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-3 py-2 hover:bg-slate-100">Admin</a>
                @endcan
            @else
                <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 hover:bg-slate-100">Masuk</a>
            @endauth
        </div>
    </div>
</header>
