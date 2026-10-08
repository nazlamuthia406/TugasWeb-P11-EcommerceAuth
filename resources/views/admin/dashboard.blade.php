<x-app-layout>
    <x-slot name="title">Panel Admin</x-slot>

    <x-slot name="header">
        <x-page-header eyebrow="🛡️ Panel admin" title="Ringkasan toko" subtitle="Pantau pengguna, produk, pesanan, dan pendapatan." />
        <x-admin-tabs />
    </x-slot>

    @php $max = max(1, $daily->max('total')); @endphp

    <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card label="Pendapatan" :value="'Rp ' . number_format($stats['revenue'], 0, ',', '.')" icon="💰" from="from-emerald-500" to="to-teal-500" hint="Tidak termasuk pesanan batal" />
            <x-stat-card label="Pesanan" :value="number_format($stats['orders'])" icon="🧾" from="from-sky-500" to="to-indigo-500" />
            <x-stat-card label="Produk" :value="number_format($stats['products'])" icon="📦" from="from-amber-400" to="to-orange-500" />
            <x-stat-card label="Pengguna" :value="number_format($stats['users'])" icon="👥" from="from-fuchsia-500" to="to-pink-500" />
        </div>

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Grafik batang pendapatan 14 hari --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 lg:col-span-2">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="font-extrabold">Pendapatan 14 hari terakhir</h2>
                        <p class="text-sm text-slate-500">Total: @rupiah($daily->sum('total'))</p>
                    </div>
                    <x-badge type="brand">Harian</x-badge>
                </div>

                <div class="flex h-56 items-end gap-1.5 sm:gap-2">
                    @foreach ($daily as $day)
                        @php $pct = $day['total'] > 0 ? max(4, round($day['total'] / $max * 100)) : 2; @endphp
                        <div class="group relative flex h-full flex-1 flex-col justify-end">
                            <div class="pointer-events-none absolute -top-9 left-1/2 z-10 hidden -translate-x-1/2 whitespace-nowrap rounded-lg bg-slate-900 px-2.5 py-1 text-xs font-semibold text-white group-hover:block">@rupiah($day['total'])</div>
                            <div class="w-full rounded-t-lg bg-gradient-to-t from-brand-600 to-fuchsia-400 transition group-hover:opacity-80" style="height: {{ $pct }}%"></div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-2 flex gap-1.5 sm:gap-2">
                    @foreach ($daily as $day)
                        <span class="flex-1 text-center text-[10px] text-slate-400">{{ $day['label'] }}</span>
                    @endforeach
                </div>
            </section>

            {{-- Produk terlaris --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6">
                <h2 class="mb-4 font-extrabold">🔥 Produk terlaris</h2>
                <ol class="space-y-4">
                    @foreach ($topProducts as $i => $product)
                        <li class="flex items-center gap-3">
                            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-slate-100 text-xs font-bold text-slate-500">{{ $i + 1 }}</span>
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-gradient-to-br {{ $product->category->gradient }} text-xl">{{ $product->emoji }}</span>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('products.show', $product) }}" class="block truncate text-sm font-semibold hover:text-brand-600">{{ $product->name }}</a>
                                <p class="text-xs text-slate-500">{{ (int) $product->sold }} terjual</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </section>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Pesanan terbaru --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white lg:col-span-2">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h2 class="font-extrabold">Pesanan terbaru</h2>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-brand-600 hover:underline">Kelola →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recentOrders as $order)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-6 py-3"><p class="font-mono font-bold">{{ $order->code }}</p><p class="text-xs text-slate-500">{{ $order->user->name }}</p></td>
                                    <td class="px-6 py-3 text-slate-500">{{ $order->created_at->diffForHumans() }}</td>
                                    <td class="px-6 py-3"><x-badge :type="$order->status_badge">{{ $order->status_label }}</x-badge></td>
                                    <td class="px-6 py-3 text-right font-bold">@rupiah($order->total)</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Produk per kategori --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6">
                <h2 class="mb-4 font-extrabold">Produk per kategori</h2>
                @php $maxCat = max(1, $categories->max('products_count')); @endphp
                <ul class="space-y-4">
                    @foreach ($categories as $category)
                        <li>
                            <div class="mb-1 flex justify-between text-sm">
                                <span class="font-medium">{{ $category->icon }} {{ $category->name }}</span>
                                <span class="text-slate-500">{{ $category->products_count }}</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-gradient-to-r {{ $category->gradient }}" style="width: {{ round($category->products_count / $maxCat * 100) }}%"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>
    </div>
</x-app-layout>
