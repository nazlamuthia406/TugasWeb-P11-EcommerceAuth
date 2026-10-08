<x-app-layout>
    <x-slot name="title">Eager Loading</x-slot>

    <x-slot name="header">
        <x-page-header eyebrow="🛡️ Panel admin · Bonus" title="Demo eager loading" :subtitle="'Membandingkan jumlah query untuk menampilkan ' . $limit . ' produk beserta kategori dan penjualnya.'" />
        <x-admin-tabs />
    </x-slot>

    @php
        $saved = $lazy['queries'] - $eager['queries'];
    @endphp

    <div class="mx-auto max-w-5xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">

        <div class="grid gap-6 md:grid-cols-2">
            {{-- Lazy --}}
            <div class="rounded-3xl border-2 border-rose-200 bg-rose-50/50 p-7">
                <x-badge type="danger">❌ Lazy loading (N+1)</x-badge>
                <p class="mt-5 text-6xl font-extrabold tracking-tight text-rose-600">{{ $lazy['queries'] }}</p>
                <p class="font-semibold text-slate-700">query · {{ $lazy['ms'] }} ms</p>
                <pre class="mt-5 overflow-x-auto rounded-xl bg-slate-900 p-4 text-xs leading-relaxed text-rose-200">$products = Product::take({{ $limit }})->get();
foreach ($products as $p) {
    $p->category->name; // +1 query
    $p->user->name;     // +1 query
}</pre>
                <p class="mt-4 text-sm text-slate-600">1 query utama + {{ $limit }} (kategori) + {{ $limit }} (penjual). Makin banyak data, makin banyak query.</p>
            </div>

            {{-- Eager --}}
            <div class="rounded-3xl border-2 border-emerald-200 bg-emerald-50/50 p-7">
                <x-badge type="success">✅ Eager loading</x-badge>
                <p class="mt-5 text-6xl font-extrabold tracking-tight text-emerald-600">{{ $eager['queries'] }}</p>
                <p class="font-semibold text-slate-700">query · {{ $eager['ms'] }} ms</p>
                <pre class="mt-5 overflow-x-auto rounded-xl bg-slate-900 p-4 text-xs leading-relaxed text-emerald-200">$products = Product::with(['category', 'user'])
    ->take({{ $limit }})->get();
foreach ($products as $p) {
    $p->category->name; // sudah dimuat
    $p->user->name;     // sudah dimuat
}</pre>
                <p class="mt-4 text-sm text-slate-600">Selalu 3 query berapa pun jumlah produknya: produk, kategori, dan penjual.</p>
            </div>
        </div>

        <div class="rounded-3xl bg-gradient-to-br from-brand-700 to-fuchsia-600 p-8 text-white shadow-2xl shadow-brand-500/30">
            <p class="text-sm font-semibold text-brand-100">Penghematan</p>
            <p class="mt-1 text-4xl font-extrabold">{{ $saved }} query lebih sedikit</p>
            <p class="mt-3 max-w-2xl text-brand-100">
                Sepanjang development, Laravel mencatat setiap lazy loading ke <code class="rounded bg-white/15 px-1.5 py-0.5">storage/logs/laravel.log</code>
                dengan awalan <code class="rounded bg-white/15 px-1.5 py-0.5">[N+1]</code> (lihat <code class="rounded bg-white/15 px-1.5 py-0.5">AppServiceProvider</code>).
                Seluruh halaman Lapak memakai <code class="rounded bg-white/15 px-1.5 py-0.5">with()</code> sehingga log tetap bersih.
            </p>
        </div>

        <p class="text-center text-xs text-slate-400">Jumlah query dihitung lewat <code>DB::enableQueryLog()</code> pada request ini.</p>
    </div>
</x-app-layout>
