<x-app-layout>
    <x-slot name="title">Pesanan Saya</x-slot>

    <x-slot name="header">
        <x-page-header eyebrow="Akun" title="Pesanan saya" subtitle="Riwayat dan status semua pesananmu." />
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        @if ($orders->isEmpty())
            <x-empty-state icon="🧾" title="Belum ada pesanan" text="Pesanan yang kamu buat akan muncul di sini.">
                <a href="{{ route('products.index') }}" class="rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white">Belanja sekarang</a>
            </x-empty-state>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="block rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-brand-300 hover:shadow-lg hover:shadow-brand-100">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="font-mono text-sm font-bold">{{ $order->code }}</p>
                                <p class="text-xs text-slate-500">{{ $order->created_at->format('d M Y, H:i') }} · {{ $order->items->count() }} produk</p>
                            </div>
                            <x-badge :type="$order->status_badge">{{ $order->status_label }}</x-badge>
                        </div>
                        <div class="mt-4 flex items-end justify-between">
                            <p class="line-clamp-1 max-w-md text-sm text-slate-500">{{ $order->items->pluck('name')->join(', ') }}</p>
                            <p class="text-lg font-extrabold text-brand-700">@rupiah($order->total)</p>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">{{ $orders->links() }}</div>
        @endif
    </div>
</x-app-layout>
