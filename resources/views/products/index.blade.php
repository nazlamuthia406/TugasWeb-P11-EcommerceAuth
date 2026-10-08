<x-app-layout>
    <x-slot name="title">Katalog</x-slot>

    @php
        // Helper kecil untuk membuat URL filter tanpa menghilangkan filter lain
        $withFilter = fn (array $changes) => route('products.index', array_filter(
            array_merge(request()->query(), $changes, ['page' => null]),
            fn ($v) => $v !== null && $v !== ''
        ));

        $activeCategory = $categories->firstWhere('slug', $filters['category']);
        $activeTag      = $tags->firstWhere('slug', $filters['tag']);
    @endphp

    <x-slot name="header">
        <x-page-header eyebrow="Katalog" title="Semua produk" :subtitle="$products->total() . ' produk ditemukan'" />
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[17rem_1fr]">

            {{-- ===== SIDEBAR FILTER ===== --}}
            <aside class="space-y-5 lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <h3 class="mb-3 text-sm font-bold uppercase tracking-wider text-slate-400">Kategori</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ $withFilter(['category' => null]) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-sm font-medium {{ ! $filters['category'] ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-100' }}">
                                <span>🛍️ Semua</span>
                            </a>
                        </li>
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ $withFilter(['category' => $category->slug]) }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-sm font-medium {{ $filters['category'] === $category->slug ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-100' }}">
                                    <span>{{ $category->icon }} {{ $category->name }}</span>
                                    <span class="rounded-full bg-slate-100 px-2 text-xs text-slate-500">{{ $category->products_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <h3 class="mb-3 text-sm font-bold uppercase tracking-wider text-slate-400">Tag</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($tags as $tag)
                            @php $on = $filters['tag'] === $tag->slug; @endphp
                            <a href="{{ $withFilter(['tag' => $on ? null : $tag->slug]) }}" class="rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset transition {{ $on ? 'bg-brand-600 text-white ring-brand-600' : 'bg-white text-slate-600 ring-slate-200 hover:bg-slate-50' }}">#{{ $tag->name }}</a>
                        @endforeach
                    </div>
                </div>

                <form method="GET" action="{{ route('products.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5">
                    @foreach (['q' => $filters['q'], 'category' => $filters['category'], 'tag' => $filters['tag']] as $name => $value)
                        @if ($value)<input type="hidden" name="{{ $name }}" value="{{ $value }}">@endif
                    @endforeach

                    <h3 class="mb-3 text-sm font-bold uppercase tracking-wider text-slate-400">Harga (Rp)</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min" min="0" placeholder="Min" value="{{ $filters['min'] }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:border-brand-400 focus:ring-4 focus:ring-brand-100">
                        <input type="number" name="max" min="0" placeholder="Maks" value="{{ $filters['max'] }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:border-brand-400 focus:ring-4 focus:ring-brand-100">
                    </div>

                    <h3 class="mb-3 mt-5 text-sm font-bold uppercase tracking-wider text-slate-400">Urutkan</h3>
                    <select name="sort" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:border-brand-400 focus:ring-4 focus:ring-brand-100">
                        @foreach (['latest' => 'Terbaru', 'price_asc' => 'Harga termurah', 'price_desc' => 'Harga termahal', 'name' => 'Nama A–Z'] as $value => $label)
                            <option value="{{ $value }}" @selected($filters['sort'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="mt-5 w-full rounded-xl bg-slate-900 py-2.5 text-sm font-bold text-white transition hover:bg-slate-700">Terapkan filter</button>
                </form>
            </aside>

            {{-- ===== HASIL ===== --}}
            <section>
                {{-- Chip filter aktif --}}
                @if ($filters['q'] || $activeCategory || $activeTag || $filters['min'] !== null || $filters['max'] !== null)
                    <div class="mb-5 flex flex-wrap items-center gap-2 text-sm">
                        <span class="text-slate-500">Filter aktif:</span>
                        @if ($filters['q'])
                            <a href="{{ $withFilter(['q' => null]) }}" class="rounded-full bg-slate-900 px-3 py-1 font-medium text-white">“{{ $filters['q'] }}” ✕</a>
                        @endif
                        @if ($activeCategory)
                            <a href="{{ $withFilter(['category' => null]) }}" class="rounded-full bg-slate-900 px-3 py-1 font-medium text-white">{{ $activeCategory->name }} ✕</a>
                        @endif
                        @if ($activeTag)
                            <a href="{{ $withFilter(['tag' => null]) }}" class="rounded-full bg-slate-900 px-3 py-1 font-medium text-white">#{{ $activeTag->name }} ✕</a>
                        @endif
                        @if ($filters['min'] !== null || $filters['max'] !== null)
                            <a href="{{ $withFilter(['min' => null, 'max' => null]) }}" class="rounded-full bg-slate-900 px-3 py-1 font-medium text-white">Harga ✕</a>
                        @endif
                        <a href="{{ route('products.index') }}" class="ml-1 font-semibold text-brand-600 hover:underline">Reset semua</a>
                    </div>
                @endif

                @if ($products->isEmpty())
                    <x-empty-state icon="🔍" title="Produk tidak ditemukan" text="Coba kata kunci lain atau longgarkan filter kategori dan harga.">
                        <a href="{{ route('products.index') }}" class="rounded-full bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white">Reset filter</a>
                    </x-empty-state>
                @else
                    <div class="grid grid-cols-2 gap-4 sm:gap-5 xl:grid-cols-3">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <div class="mt-10">{{ $products->links() }}</div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
