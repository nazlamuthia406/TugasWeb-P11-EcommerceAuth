<x-app-layout>
    <x-slot name="title">Pesanan {{ $order->code }}</x-slot>

    <x-slot name="header">
        <x-page-header eyebrow="Detail pesanan" :title="$order->code" :subtitle="'Dibuat ' . $order->created_at->format('d M Y, H:i')">
            <x-badge :type="$order->status_badge" class="!px-4 !py-1.5 !text-sm">{{ $order->status_label }}</x-badge>
        </x-page-header>
    </x-slot>

    <div class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">

        {{-- Timeline status --}}
        @if ($order->status === 'cancelled')
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 text-rose-700">❌ Pesanan ini telah dibatalkan.</div>
        @else
            @php
                $steps   = ['pending' => ['🕒', 'Dipesan'], 'paid' => ['💳', 'Dibayar'], 'shipped' => ['🚚', 'Dikirim'], 'completed' => ['✅', 'Selesai']];
                $current = array_search($order->status, array_keys($steps), true);
            @endphp
            <ol class="grid grid-cols-4 gap-2 rounded-2xl border border-slate-200 bg-white p-5">
                @foreach ($steps as $key => [$icon, $label])
                    @php $i = array_search($key, array_keys($steps), true); $done = $i <= $current; @endphp
                    <li class="text-center">
                        <div class="mx-auto grid h-12 w-12 place-items-center rounded-full text-xl {{ $done ? 'bg-gradient-to-br from-brand-500 to-fuchsia-500 text-white shadow-lg shadow-brand-500/30' : 'bg-slate-100 grayscale' }}">{{ $icon }}</div>
                        <p class="mt-2 text-xs font-semibold {{ $done ? 'text-slate-800' : 'text-slate-400' }}">{{ $label }}</p>
                    </li>
                @endforeach
            </ol>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1fr_20rem]">
            {{-- Item --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-100 px-5 py-4 font-bold">Produk dipesan</div>
                <ul class="divide-y divide-slate-100">
                    @foreach ($order->items as $item)
                        <li class="flex items-center gap-4 px-5 py-4">
                            <span class="grid h-14 w-14 shrink-0 place-items-center rounded-xl bg-slate-100 text-3xl">{{ $item->product?->emoji ?? '📦' }}</span>
                            <div class="min-w-0 flex-1">
                                @if ($item->product)
                                    <a href="{{ route('products.show', $item->product) }}" class="block truncate font-semibold hover:text-brand-600">{{ $item->name }}</a>
                                @else
                                    <p class="truncate font-semibold">{{ $item->name }} <span class="text-xs font-normal text-slate-400">(produk telah dihapus)</span></p>
                                @endif
                                <p class="text-xs text-slate-500">{{ $item->quantity }} × @rupiah($item->price)</p>
                            </div>
                            <p class="font-bold">@rupiah($item->subtotal)</p>
                        </li>
                    @endforeach
                </ul>
                <div class="flex items-center justify-between bg-slate-50 px-5 py-4">
                    <span class="font-semibold">Total</span>
                    <span class="text-2xl font-extrabold text-brand-700">@rupiah($order->total)</span>
                </div>
            </div>

            {{-- Pengiriman --}}
            <aside class="h-fit rounded-2xl border border-slate-200 bg-white p-5">
                <h3 class="mb-3 font-bold">📍 Pengiriman</h3>
                <p class="font-semibold">{{ $order->shipping_name }}</p>
                <p class="mt-1 whitespace-pre-line text-sm text-slate-500">{{ $order->shipping_address }}</p>
            </aside>
        </div>

        <a href="{{ route('orders.index') }}" class="inline-block text-sm font-semibold text-brand-600 hover:underline">← Semua pesanan</a>
    </div>
</x-app-layout>
