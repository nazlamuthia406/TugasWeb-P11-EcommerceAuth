<x-app-layout>
    <x-slot name="title">Beranda</x-slot>

    {{-- ===== HERO ===== --}}
    <section class="relative overflow-hidden bg-slate-950 text-white"
             style="background-image: radial-gradient(60% 60% at 15% 0%, rgba(124,58,237,.6), transparent), radial-gradient(50% 50% at 95% 15%, rgba(217,70,239,.45), transparent), radial-gradient(45% 45% at 55% 110%, rgba(6,182,212,.35), transparent);">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-24">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-1.5 text-xs font-semibold backdrop-blur">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-400"></span> {{ $stats['products'] }} produk siap dikirim
                </span>
                <h1 class="mt-6 text-4xl font-extrabold leading-[1.1] tracking-tight sm:text-5xl lg:text-6xl">
                    Temukan apa pun,<br>
                    <span class="bg-gradient-to-r from-brand-300 via-fuchsia-300 to-amber-200 bg-clip-text text-transparent">jual apa saja.</span>
                </h1>
                <p class="mt-5 max-w-lg text-lg text-slate-300">Lapak mempertemukan pembeli dan penjual dalam satu tempat — dari laptop kuliah sampai bika ambon khas Medan.</p>

                <form action="{{ route('products.index') }}" method="GET" class="mt-8 flex max-w-lg overflow-hidden rounded-full bg-white p-1.5 shadow-2xl shadow-brand-900/40">
                    <input type="search" name="q" placeholder="Cari produk impianmu…" class="min-w-0 flex-1 bg-transparent px-5 text-slate-800 outline-none placeholder:text-slate-400">
                    <button class="rounded-full bg-gradient-to-r from-brand-600 to-fuchsia-600 px-6 py-3 text-sm font-bold text-white transition hover:opacity-90">Cari</button>
                </form>

                <dl class="mt-10 flex flex-wrap gap-x-10 gap-y-4">
                    @foreach ([[$stats['products'], 'Produk'], [$stats['sellers'], 'Penjual aktif'], [$stats['orders'], 'Pesanan diproses']] as [$value, $label])
                        <div>
                            <dt class="text-3xl font-extrabold">{{ number_format($value) }}</dt>
                            <dd class="text-sm text-slate-400">{{ $label }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- Kolase kartu produk --}}
            <div class="relative mx-auto hidden h-[26rem] w-full max-w-md lg:block">
                @foreach ($featured->take(3)->values() as $i => $p)
                    @php
                        $pos = [
                            'left-0 top-8 -rotate-6',
                            'right-0 top-0 rotate-6',
                            'left-1/2 bottom-0 -translate-x-1/2 rotate-1',
                        ][$i];
                    @endphp
                    <a href="{{ route('products.show', $p) }}" class="absolute {{ $pos }} w-56 rounded-3xl border border-white/20 bg-white p-3 text-slate-800 shadow-2xl shadow-black/40 transition duration-300 hover:z-10 hover:scale-105">
                        <div class="grid h-32 place-items-center rounded-2xl bg-gradient-to-br {{ $p->category->gradient }} text-6xl">{{ $p->emoji }}</div>
                        <p class="mt-3 line-clamp-1 text-sm font-semibold">{{ $p->name }}</p>
                        <p class="font-extrabold text-brand-700">@rupiah($p->price)</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== KEUNGGULAN ===== --}}
    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-6 sm:grid-cols-2 sm:px-6 lg:grid-cols-4 lg:px-8">
            @foreach ([['🚚', 'Pengiriman cepat', 'Dikirim ke seluruh Indonesia'], ['🔒', 'Transaksi aman', 'Dilindungi CSRF & autentikasi'], ['↩️', 'Retur mudah', 'Garansi uang kembali'], ['💬', 'Dukungan 24/7', 'Siap membantu kapan saja']] as [$icon, $t, $d])
                <div class="flex items-center gap-4">
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-brand-50 text-2xl">{{ $icon }}</span>
                    <div>
                        <p class="font-bold">{{ $t }}</p>
                        <p class="text-sm text-slate-500">{{ $d }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="mx-auto max-w-7xl space-y-16 px-4 py-14 sm:px-6 lg:px-8">

        {{-- ===== KATEGORI ===== --}}
        <section>
            <div class="mb-6 flex items-end justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-brand-600">Jelajahi</p>
                    <h2 class="text-2xl font-extrabold tracking-tight">Belanja per kategori</h2>
                </div>
                <a href="{{ route('products.index') }}" class="text-sm font-semibold text-brand-600 hover:underline">Lihat semua →</a>
            </div>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7">
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                       class="group relative overflow-hidden rounded-2xl bg-gradient-to-br {{ $category->gradient }} p-5 text-white shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
                        <span class="absolute -bottom-4 -right-2 text-7xl opacity-30 transition duration-300 group-hover:scale-125 group-hover:opacity-50">{{ $category->icon }}</span>
                        <span class="relative text-3xl">{{ $category->icon }}</span>
                        <p class="relative mt-6 font-bold leading-tight">{{ $category->name }}</p>
                        <p class="relative text-xs text-white/80">{{ $category->products_count }} produk</p>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- ===== PRODUK PILIHAN ===== --}}
        @if ($featured->isNotEmpty())
            <section>
                <div class="mb-6">
                    <p class="text-xs font-bold uppercase tracking-widest text-brand-600">Dikurasi editor</p>
                    <h2 class="text-2xl font-extrabold tracking-tight">Produk pilihan ⭐</h2>
                </div>
                <div class="grid grid-cols-2 gap-4 sm:gap-5 lg:grid-cols-4">
                    @foreach ($featured as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ===== BARU MASUK ===== --}}
        <section>
            <div class="mb-6 flex items-end justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-brand-600">Fresh</p>
                    <h2 class="text-2xl font-extrabold tracking-tight">Baru masuk 🆕</h2>
                </div>
                <a href="{{ route('products.index', ['sort' => 'latest']) }}" class="text-sm font-semibold text-brand-600 hover:underline">Lihat semua →</a>
            </div>
            <div class="grid grid-cols-2 gap-4 sm:gap-5 lg:grid-cols-4">
                @foreach ($latest as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>

        {{-- ===== CTA PENJUAL ===== --}}
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-700 via-brand-600 to-fuchsia-600 px-8 py-12 text-white shadow-2xl shadow-brand-500/30 sm:px-14">
            <div class="absolute -right-10 -top-10 text-[12rem] opacity-20">🛍️</div>
            <div class="relative max-w-xl">
                <h2 class="text-3xl font-extrabold tracking-tight">Punya barang untuk dijual?</h2>
                <p class="mt-2 text-brand-100">Buka lapakmu dalam semenit. Atur harga, stok, dan tag produk — dashboard-mu siap memantau pesanan.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    @auth
                        <a href="{{ route('products.create') }}" class="rounded-full bg-white px-6 py-3 text-sm font-bold text-brand-700 shadow-lg transition hover:scale-105">+ Tambah produk</a>
                        <a href="{{ route('products.manage') }}" class="rounded-full border border-white/40 px-6 py-3 text-sm font-semibold hover:bg-white/10">Kelola produk</a>
                    @else
                        <a href="{{ route('register') }}" class="rounded-full bg-white px-6 py-3 text-sm font-bold text-brand-700 shadow-lg transition hover:scale-105">Daftar gratis</a>
                        <a href="{{ route('login') }}" class="rounded-full border border-white/40 px-6 py-3 text-sm font-semibold hover:bg-white/10">Sudah punya akun</a>
                    @endauth
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
