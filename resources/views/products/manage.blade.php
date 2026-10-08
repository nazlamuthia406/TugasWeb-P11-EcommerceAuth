<x-app-layout>
    <x-slot name="title">Kelola Produk</x-slot>

    <x-slot name="header">
        <x-page-header eyebrow="Penjual" title="Kelola produk"
            :subtitle="auth()->user()->canManageAllProducts() ? 'Sebagai ' . auth()->user()->role_label . ', kamu bisa melihat semua produk.' : 'Daftar produk milikmu.'">
            <a href="{{ route('products.create') }}" class="rounded-full bg-gradient-to-r from-brand-600 to-fuchsia-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-brand-500/30 hover:opacity-90">+ Tambah produk</a>
        </x-page-header>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if ($products->isEmpty())
            <x-empty-state icon="📦" title="Belum ada produk" text="Mulai berjualan dengan menambahkan produk pertamamu.">
                <a href="{{ route('products.create') }}" class="rounded-full bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white">+ Tambah produk</a>
            </x-empty-state>
        @else
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Produk</th>
                                <th class="px-5 py-3">Penjual</th>
                                <th class="px-5 py-3 text-right">Harga</th>
                                <th class="px-5 py-3 text-right">Stok</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($products as $product)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-3">
                                            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-gradient-to-br {{ $product->category->gradient }} text-2xl">{{ $product->emoji }}</span>
                                            <div class="min-w-0">
                                                <a href="{{ route('products.show', $product) }}" class="block max-w-xs truncate font-semibold hover:text-brand-600">{{ $product->name }}</a>
                                                <p class="text-xs text-slate-500">{{ $product->category->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-slate-600">
                                        {{ $product->user->name }}
                                        @if ($product->user_id === auth()->id())<x-badge type="success" class="ml-1">Milikmu</x-badge>@endif
                                    </td>
                                    <td class="px-5 py-3 text-right font-semibold">@rupiah($product->price)</td>
                                    <td class="px-5 py-3 text-right {{ $product->stock < 1 ? 'font-bold text-rose-600' : '' }}">{{ $product->stock }}</td>
                                    <td class="px-5 py-3">
                                        <div class="flex justify-end gap-2">
                                            @can('update', $product)
                                                <a href="{{ route('products.edit', $product) }}" class="rounded-full px-3 py-1.5 text-xs font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-100">Edit</a>
                                            @endcan
                                            @can('delete', $product)
                                                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-full px-3 py-1.5 text-xs font-semibold text-rose-600 ring-1 ring-rose-200 hover:bg-rose-50">Hapus</button>
                                                </form>
                                            @else
                                                <span class="px-2 py-1.5 text-xs text-slate-400" title="Hanya pemilik atau admin yang dapat menghapus">🔒</span>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8">{{ $products->links() }}</div>
        @endif
    </div>
</x-app-layout>
