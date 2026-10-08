@props(['label', 'value', 'icon' => '📊', 'from' => 'from-brand-500', 'to' => 'to-fuchsia-500', 'hint' => null])

<div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5">
    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-gradient-to-br {{ $from }} {{ $to }} opacity-10"></div>
    <div class="flex items-center gap-4">
        <span class="grid h-12 w-12 place-items-center rounded-2xl bg-gradient-to-br {{ $from }} {{ $to }} text-2xl shadow-lg">{{ $icon }}</span>
        <div class="min-w-0">
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="truncate text-2xl font-extrabold tracking-tight text-slate-900">{{ $value }}</p>
            @if ($hint)<p class="text-xs text-slate-400">{{ $hint }}</p>@endif
        </div>
    </div>
</div>
