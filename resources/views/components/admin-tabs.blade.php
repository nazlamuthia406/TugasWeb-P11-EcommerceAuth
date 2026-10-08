@php
    $tabs = [
        'admin.dashboard'   => ['📊', 'Ringkasan'],
        'admin.users.index' => ['👥', 'Pengguna'],
        'admin.orders.index'=> ['🧾', 'Pesanan'],
        'admin.eager'       => ['⚡', 'Eager Loading'],
    ];
@endphp

<nav class="mt-6 flex gap-2 overflow-x-auto pb-1">
    @foreach ($tabs as $route => [$icon, $label])
        <a href="{{ route($route) }}"
           class="flex items-center gap-2 whitespace-nowrap rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->routeIs($route) ? 'bg-slate-900 text-white shadow-lg' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            <span>{{ $icon }}</span>{{ $label }}
        </a>
    @endforeach
</nav>
