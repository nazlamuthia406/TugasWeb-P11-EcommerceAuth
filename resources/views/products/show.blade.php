<x-app-layout>
    <x-slot name="title">{{ $product->name }}</x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex flex-wrap items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-600">Beranda</a> <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-brand-600">Katalog</a> <span>/</span>
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-brand-600">{{ $product->category->name }}</a> <span>/</span>
            <span class="truncate font-medium text-slate-700">{{ $product->name }}</span>
        </nav>

        <div class="grid gap-10 lg:grid-cols-2">

            {{-- Visual produk --}}
            <div class="relative grid min-h-[22rem] place-items-center overflow-hidden rounded-3xl bg-gradient-to-br {{ $product->category->gradient }} shadow-2xl lg:min-h-[30rem]">
                <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/15"></div>
                <div class="absolute -bottom-20 -left-10 h-56 w-56 rounded-full bg-black/10"></div>
                <span class="relative text-[9rem] drop-shadow-2xl sm:text-[11rem]">{{ $product->emoji }}</span>
                @if ($product->is_featured)
                    <span class="absolute left-5 top-5 rounded-full bg-white/95 px-4 py-1.5 text-xs font-bold text-brand-700 shadow">⭐ Produk pilihan</span>
                @endif
            </div>

            {{-- Detail --}}
            <div>
                <x-badge type="brand">{{ $product->category->icon }} {{ $product->category->name }}</x-badge>
                <h1 class="mt-3 text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">{{ $product->name }}</h1>

                <p class="mt-4 text-4xl font-extrabold text-brand-700">@rupiah($product->price)</p>

                <div class="mt-4 flex flex-wrap items-center gap-2">
                    @if ($product->stock < 1)
                        <x-badge type="danger">Stok habis</x-badge>
                    @elseif ($product->stock <= 10)
                        <x-badge type="warning">Tinggal {{ $product->stock }} lagi</x-badge>
                    @else
                        <x-badge type="success">Tersedia · {{ $product->stock }} stok</x-badge>
                    @endif
                    @foreach ($product->tags as $tag)
                        <a href="{{ route('products.index', ['tag' => $tag->slug]) }}"><x-badge>#{{ $tag->name }}</x-badge></a>
                    @endforeach
                </div>

                <p class="mt-6 leading-relaxed text-slate-600">{{ $product->description }}</p>

                {{-- Penjual --}}
                <div class="mt-6 flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4">
                    <span class="grid h-11 w-11 place-items-center rounded-full bg-gradient-to-br from-brand-500 to-fuchsia-500 text-sm font-bold text-white">{{ $product->user->initials }}</span>
                    <div>
                        <p class="text-xs text-slate-500">Dijual oleh</p>
                        <p class="font-semibold">{{ $product->user->name }}</p>
                    </div>
                    <x-badge :type="$product->user->role_badge" class="ml-auto">{{ $product->user->role_label }}</x-badge>
                </div>

                {{-- Beli --}}
                <div class="mt-6">
                    @auth
                        @if ($product->stock > 0)
                            <form method="POST" action="{{ route('cart.store', $product) }}"
                                  x-data="{ qty: 1, max: {{ min($product->stock, 99) }} }" class="flex flex-wrap items-center gap-3">
                                @csrf
                                <div class="flex items-center rounded-full border border-slate-200 bg-white">
                                    <button type="button" @click="qty = Math.max(1, qty - 1)" class="grid h-12 w-12 place-items-center text-xl text-slate-500 hover:text-brand-600">−</button>
                                    <input type="number" name="quantity" x-model.number="qty" min="1" :max="max" class="w-12 border-0 bg-transparent p-0 text-center font-bold outline-none focus:ring-0">
                                    <button type="button" @click="qty = Math.min(max, qty + 1)" class="grid h-12 w-12 place-items-center text-xl text-slate-500 hover:text-brand-600">+</button>
                                </div>
                                <button type="submit" class="flex-1 rounded-full bg-gradient-to-r from-brand-600 to-fuchsia-600 px-8 py-3.5 text-center font-bold text-white shadow-xl shadow-brand-500/30 transition hover:opacity-90 sm:flex-none">🛒 Tambah ke keranjang</button>
                            </form>
                        @else
                            <button disabled class="w-full cursor-not-allowed rounded-full bg-slate-200 py-3.5 font-bold text-slate-400">Stok habis</button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="inline-block rounded-full bg-slate-900 px-8 py-3.5 font-bold text-white transition hover:bg-slate-700">Masuk untuk membeli</a>
                    @endauth
                </div>

                {{-- Kontrol pengelola: ditampilkan sesuai Policy (UX saja — keamanan tetap di controller) --}}
                @canany(['update', 'delete'], $product)
                    <div class="mt-8 rounded-2xl border border-dashed border-amber-300 bg-amber-50 p-4">
                        <p class="mb-3 text-xs font-bold uppercase tracking-widest text-amber-700">Kontrol pengelola</p>
                        <div class="flex flex-wrap gap-2">
                            @can('update', $product)
                                <a href="{{ route('products.edit', $product) }}" class="rounded-full bg-white px-5 py-2 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50">✏️ Edit</a>
                            @endcan
                            @can('delete', $product)
                                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-full bg-white px-5 py-2 text-sm font-semibold text-rose-600 ring-1 ring-rose-200 hover:bg-rose-50">🗑️ Hapus</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @endcanany
            </div>
        </div>

        {{-- Produk terkait --}}
        @if ($related->isNotEmpty())
            <section class="mt-16">
                <h2 class="mb-6 text-2xl font-extrabold tracking-tight">Produk serupa</h2>
                <div class="grid grid-cols-2 gap-4 sm:gap-5 lg:grid-cols-4">
                    @foreach ($related as $item)
                        <x-product-card :product="$item" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-app-layout>
