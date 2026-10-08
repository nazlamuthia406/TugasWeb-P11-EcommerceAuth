@props(['product'])

{{-- Kartu produk. Pastikan relasi 'category' & 'user' sudah di-eager load oleh controller. --}}
<a href="{{ route('products.show', $product) }}"
   class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition duration-300 hover:-translate-y-1 hover:border-brand-200 hover:shadow-2xl hover:shadow-brand-100">

    <div class="relative grid h-44 place-items-center overflow-hidden bg-gradient-to-br {{ $product->category->gradient }}">
        <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/15"></div>
        <div class="absolute -bottom-8 -left-6 h-24 w-24 rounded-full bg-black/10"></div>
        <span class="relative text-6xl drop-shadow-lg transition duration-300 group-hover:scale-110 group-hover:-rotate-6">{{ $product->emoji }}</span>

        @if ($product->is_featured)
            <span class="absolute left-3 top-3 rounded-full bg-white/95 px-2.5 py-1 text-[11px] font-bold text-brand-700 shadow">⭐ Pilihan</span>
        @endif
        @if ($product->stock < 1)
            <span class="absolute inset-0 grid place-items-center bg-slate-900/60 text-sm font-bold tracking-wide text-white">STOK HABIS</span>
        @elseif ($product->stock <= 10)
            <span class="absolute right-3 top-3 rounded-full bg-rose-500 px-2.5 py-1 text-[11px] font-bold text-white shadow">Sisa {{ $product->stock }}</span>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-4">
        <span class="text-xs font-semibold text-brand-600">{{ $product->category->name }}</span>
        <h3 class="mt-1 line-clamp-2 min-h-[2.75rem] font-semibold leading-snug text-slate-800">{{ $product->name }}</h3>
        <p class="mt-3 text-lg font-extrabold text-slate-900">@rupiah($product->price)</p>
        <p class="mt-0.5 truncate text-xs text-slate-500">Dijual oleh {{ $product->user->name }}</p>
    </div>
</a>
