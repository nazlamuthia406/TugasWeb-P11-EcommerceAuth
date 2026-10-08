<x-app-layout>
    <x-slot name="title">Pesanan</x-slot>

    <x-slot name="header">
        <x-page-header eyebrow="🛡️ Panel admin" title="Manajemen pesanan" subtitle="Perbarui status pesanan pelanggan." />
        <x-admin-tabs />
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">

        {{-- Filter status --}}
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.orders.index') }}" class="rounded-full px-4 py-1.5 text-sm font-semibold ring-1 ring-inset {{ ! $status ? 'bg-slate-900 text-white ring-slate-900' : 'bg-white text-slate-600 ring-slate-200 hover:bg-slate-50' }}">Semua</a>
            @foreach (\App\Models\Order::STATUSES as $key => $label)
                <a href="{{ route('admin.orders.index', ['status' => $key]) }}" class="rounded-full px-4 py-1.5 text-sm font-semibold ring-1 ring-inset {{ $status === $key ? 'bg-slate-900 text-white ring-slate-900' : 'bg-white text-slate-600 ring-slate-200 hover:bg-slate-50' }}">{{ $label }}</a>
            @endforeach
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Pesanan</th>
                            <th class="px-5 py-3">Pembeli</th>
                            <th class="px-5 py-3 text-right">Total</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Ubah status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-5 py-3">
                                    <a href="{{ route('orders.show', $order) }}" class="font-mono font-bold hover:text-brand-600">{{ $order->code }}</a>
                                    <p class="text-xs text-slate-500">{{ $order->created_at->format('d M Y, H:i') }} · {{ $order->items->count() }} produk</p>
                                </td>
                                <td class="px-5 py-3">{{ $order->user->name }}</td>
                                <td class="px-5 py-3 text-right font-bold">@rupiah($order->total)</td>
                                <td class="px-5 py-3"><x-badge :type="$order->status_badge">{{ $order->status_label }}</x-badge></td>
                                <td class="px-5 py-3">
                                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="flex justify-end gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm outline-none focus:border-brand-400">
                                            @foreach (\App\Models\Order::STATUSES as $key => $label)
                                                <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-700">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-12 text-center text-slate-500">Belum ada pesanan dengan status ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $orders->links() }}
    </div>
</x-app-layout>
