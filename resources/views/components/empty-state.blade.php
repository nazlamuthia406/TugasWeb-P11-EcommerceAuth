@props(['icon' => '🔍', 'title', 'text' => null])

<div {{ $attributes->merge(['class' => 'rounded-3xl border-2 border-dashed border-slate-200 bg-white px-6 py-16 text-center']) }}>
    <div class="mx-auto mb-4 grid h-20 w-20 place-items-center rounded-full bg-slate-100 text-4xl">{{ $icon }}</div>
    <h3 class="text-lg font-bold text-slate-800">{{ $title }}</h3>
    @if ($text)<p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">{{ $text }}</p>@endif
    @if (trim((string) $slot) !== '')<div class="mt-6 flex justify-center gap-2">{{ $slot }}</div>@endif
</div>
