<x-app-layout>
    <x-slot name="title">Keranjang</x-slot>

    <x-slot name="header">
        <x-page-header eyebrow="Belanja" title="Keranjang kamu" :subtitle="$items->count() . ' jenis produk di keranjang'" />
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if ($items->isEmpty())
            <x-empty-state icon="🛒" title="Keranjangmu masih kosong" text="Yuk, jelajahi katalog dan temukan produk favoritmu.">
                <a href="{{ route('products.index') }}" class="rounded-full bg-gradient-to-r from-brand-600 to-fuchsia-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-brand-500/30">Mulai belanja</a>
            </x-empty-state>
        @else
            <div class="grid gap-8 lg:grid-cols-[1fr_24rem]">

                {{-- Daftar item --}}
                <div class="space-y-4">
                    @foreach ($items as $item)
                        @php $p = $item->product; @endphp
                        <div class="flex gap-4 rounded-2xl border border-slate-200 bg-white p-4">
                            <a href="{{ route('products.show', $p) }}" class="grid h-24 w-24 shrink-0 place-items-center rounded-xl bg-gradient-to-br {{ $p->category->gradient }} text-5xl">{{ $p->emoji }}</a>

                            <div class="flex min-w-0 flex-1 flex-col">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <a href="{{ route('products.show', $p) }}" class="line-clamp-2 font-semibold hover:text-brand-600">{{ $p->name }}</a>
                                        <p class="text-xs text-slate-500">{{ $p->category->name }} · @rupiah($p->price) / unit</p>
                                    </div>
                                    <form method="POST" action="{{ route('cart.destroy', $p) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="grid h-8 w-8 place-items-center rounded-full text-slate-400 transition hover:bg-rose-50 hover:text-rose-600" aria-label="Hapus">🗑️</button>
                                    </form>
                                </div>

                                <div class="mt-auto flex items-end justify-between pt-3">
                                    <form method="POST" action="{{ route('cart.update', $p) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <label class="text-xs text-slate-500" for="qty-{{ $p->id }}">Jumlah</label>
                                        <input id="qty-{{ $p->id }}" type="number" name="quantity" min="1" max="{{ min(99, max(1, $p->stock)) }}" value="{{ $item->quantity }}"
                                               class="w-16 rounded-lg border border-slate-200 px-2 py-1 text-center text-sm font-semibold outline-none focus:border-brand-400 focus:ring-4 focus:ring-brand-100">
                                        <button type="submit" class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-200">Ubah</button>
                                    </form>
                                    <p class="text-lg font-extrabold">@rupiah($item->subtotal)</p>
                                </div>

                                @if ($p->stock < $item->quantity || $p->stock < 1)
                                    <p class="mt-2 text-xs font-semibold text-rose-600">⚠️ Stok tersisa {{ $p->stock }} — kurangi jumlah sebelum checkout.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Ringkasan + checkout --}}
                <aside class="lg:sticky lg:top-24 lg:self-start">
                    <form method="POST" action="{{ route('checkout') }}" class="space-y-5 rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/50">
                        @csrf
                        <h2 class="text-lg font-extrabold">Ringkasan pesanan</h2>

                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between text-slate-500"><dt>Subtotal ({{ $items->sum('quantity') }} barang)</dt><dd>@rupiah($total)</dd></div>
                            <div class="flex justify-between text-slate-500"><dt>Ongkos kirim</dt><dd class="font-semibold text-emerald-600">Gratis</dd></div>
                            <div class="flex items-end justify-between border-t border-dashed border-slate-200 pt-3">
                                <dt class="font-semibold">Total</dt>
                                <dd class="text-2xl font-extrabold text-brand-700">@rupiah($total)</dd>
                            </div>
                        </dl>

                        <div>
                            <label for="shipping_name" class="mb-1.5 block text-sm font-semibold">Nama penerima</label>
                            <input id="shipping_name" name="shipping_name" type="text" value="{{ old('shipping_name', auth()->user()->name) }}" required
                                   class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none focus:ring-4 {{ $errors->has('shipping_name') ? 'border-rose-400 focus:ring-rose-100' : 'border-slate-200 focus:border-brand-400 focus:ring-brand-100' }}">
                            @error('shipping_name')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="shipping_address" class="mb-1.5 block text-sm font-semibold">Alamat pengiriman</label>
                            <textarea id="shipping_address" name="shipping_address" rows="3" required placeholder="Jalan, nomor, kota, kode pos"
                                      class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none focus:ring-4 {{ $errors->has('shipping_address') ? 'border-rose-400 focus:ring-rose-100' : 'border-slate-200 focus:border-brand-400 focus:ring-brand-100' }}">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="w-full rounded-full bg-gradient-to-r from-brand-600 to-fuchsia-600 py-3.5 font-bold text-white shadow-xl shadow-brand-500/30 transition hover:opacity-90">Buat pesanan →</button>
                        <p class="text-center text-xs text-slate-400">🔒 Stok dikunci dan dicek ulang saat pesanan dibuat.</p>
                    </form>
                </aside>
            </div>
        @endif
    </div>
</x-app-layout>
