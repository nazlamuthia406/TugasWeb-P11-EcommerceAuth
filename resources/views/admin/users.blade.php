<x-app-layout>
    <x-slot name="title">Pengguna</x-slot>

    <x-slot name="header">
        <x-page-header eyebrow="🛡️ Panel admin" title="Manajemen pengguna" subtitle="Atur role admin, editor, atau pengguna biasa." />
        <x-admin-tabs />
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">

        <form method="GET" action="{{ route('admin.users.index') }}" class="flex max-w-md gap-2">
            <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama atau email…" class="min-w-0 flex-1 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm outline-none focus:border-brand-400 focus:ring-4 focus:ring-brand-100">
            <button class="rounded-full bg-slate-900 px-5 text-sm font-semibold text-white">Cari</button>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Pengguna</th>
                            <th class="px-5 py-3 text-center">Produk</th>
                            <th class="px-5 py-3 text-center">Pesanan</th>
                            <th class="px-5 py-3">Role</th>
                            <th class="px-5 py-3 text-right">Ubah role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-gradient-to-br from-brand-500 to-fuchsia-500 text-xs font-bold text-white">{{ $user->initials }}</span>
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold">{{ $user->name }} @if ($user->is(auth()->user()))<span class="text-xs font-normal text-slate-400">(kamu)</span>@endif</p>
                                            <p class="truncate text-xs text-slate-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-center">{{ $user->products_count }}</td>
                                <td class="px-5 py-3 text-center">{{ $user->orders_count }}</td>
                                <td class="px-5 py-3"><x-badge :type="$user->role_badge">{{ $user->role_label }}</x-badge></td>
                                <td class="px-5 py-3">
                                    @if ($user->is(auth()->user()))
                                        <p class="text-right text-xs text-slate-400">Tidak dapat mengubah akun sendiri</p>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex justify-end gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm outline-none focus:border-brand-400">
                                                @foreach (\App\Models\User::ROLES as $role)
                                                    <option value="{{ $role }}" @selected($user->role === $role)>{{ ucfirst($role) }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-700">Simpan</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-12 text-center text-slate-500">Tidak ada pengguna yang cocok.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $users->links() }}
    </div>
</x-app-layout>
