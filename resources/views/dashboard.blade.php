<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    @php $user = auth()->user(); @endphp

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <span class="grid h-16 w-16 place-items-center rounded-2xl bg-gradient-to-br from-brand-500 to-fuchsia-500 text-xl font-extrabold text-white shadow-xl shadow-brand-500/30">{{ $user->initials }}</span>
                <div>
                    <p class="text-sm text-slate-500">Selamat datang kembali,</p>
                    <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">{{ $user->name }} 👋</h1>
                    <x-badge :type="$user->role_badge" class="mt-1">Role: {{ $user->role_label }}</x-badge>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('products.create') }}" class="rounded-full bg-gradient-to-r from-brand-600 to-fuchsia-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-brand-500/30 hover:opacity-90">+ Tambah produk</a>
                <a href="{{ route('products.index') }}" class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold hover:bg-slate-50">Belanja</a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">

        {{-- Statistik --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card label="Pesanan saya" :value="$stats['orders']" icon="🧾" from="from-sky-500" to="to-indigo-500" />
            <x-stat-card label="Total belanja" :value="'Rp ' . number_format($stats['spent'], 0, ',', '.')" icon="💸" from="from-emerald-500" to="to-teal-500" hint="Tidak termasuk pesanan batal" />
            <x-stat-card label="Produk saya" :value="$stats['my_products']" icon="📦" from="from-amber-400" to="to-orange-500" />
            <x-stat-card label="Produk di Lapak" :value="$stats['all_products']" icon="🛍️" from="from-fuchsia-500" to="to-pink-500" />
        </div>

        <div class="grid gap-8 lg:grid-cols-3">

            {{-- Pesanan terbaru --}}
            <section class="lg:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-extrabold">Pesanan terbaru</h2>
                    <a href="{{ route('orders.index') }}" class="text-sm font-semibold text-brand-600 hover:underline">Lihat semua →</a>
                </div>
                @forelse ($orders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="mb-3 flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-brand-300">
                        <div class="min-w-0">
                            <p class="font-mono text-sm font-bold">{{ $order->code }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $order->items->pluck('name')->join(', ') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold">@rupiah($order->total)</p>
                            <x-badge :type="$order->status_badge">{{ $order->status_label }}</x-badge>
                        </div>
                    </a>
                @empty
                    <x-empty-state icon="🧾" title="Belum ada pesanan" text="Pesananmu akan tampil di sini setelah checkout." class="!py-10" />
                @endforelse

                <div class="mt-8 mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-extrabold">Produk yang kamu jual</h2>
                    <a href="{{ route('products.manage') }}" class="text-sm font-semibold text-brand-600 hover:underline">Kelola →</a>
                </div>
                @forelse ($myProducts as $product)
                    <div class="mb-3 flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-gradient-to-br {{ $product->category->gradient }} text-2xl">{{ $product->emoji }}</span>
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('products.show', $product) }}" class="block truncate font-semibold hover:text-brand-600">{{ $product->name }}</a>
                            <p class="text-xs text-slate-500">Stok {{ $product->stock }}</p>
                        </div>
                        <p class="font-bold">@rupiah($product->price)</p>
                    </div>
                @empty
                    <x-empty-state icon="📦" title="Kamu belum menjual apa pun" class="!py-10">
                        <a href="{{ route('products.create') }}" class="rounded-full bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white">+ Tambah produk</a>
                    </x-empty-state>
                @endforelse
            </section>

            {{-- Matriks hak akses role --}}
            <aside>
                <h2 class="mb-4 text-lg font-extrabold">Hak akses role kamu</h2>
                @php
                    $abilities = [
                        ['Belanja & checkout',               true],
                        ['Tambah produk sendiri',            true],
                        ['Edit & hapus produk sendiri',      true],
                        ['Edit produk milik orang lain',     $user->can('manage-all-products')],
                        ['Hapus produk milik orang lain',    $user->isAdmin()],
                        ['Akses panel /admin',               $user->can('access-admin')],
                        ['Ubah role & status pesanan',       $user->can('access-admin')],
                    ];
                @endphp
                <ul class="divide-y divide-slate-100 rounded-2xl border border-slate-200 bg-white">
                    @foreach ($abilities as [$label, $allowed])
                        <li class="flex items-center justify-between gap-3 px-4 py-3 text-sm">
                            <span class="{{ $allowed ? 'text-slate-700' : 'text-slate-400' }}">{{ $label }}</span>
                            <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full text-xs font-bold {{ $allowed ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-400' }}">{{ $allowed ? '✓' : '✕' }}</span>
                        </li>
                    @endforeach
                </ul>
                <p class="mt-3 text-xs text-slate-400">Pengecekan hak akses dilakukan di server (middleware &amp; policy), bukan hanya menyembunyikan tombol.</p>
            </aside>
        </div>
    </div>
</x-app-layout>
