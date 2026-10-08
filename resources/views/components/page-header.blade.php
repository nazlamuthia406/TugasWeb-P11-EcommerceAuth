@props(['title', 'subtitle' => null, 'eyebrow' => null])

<div class="flex flex-wrap items-end justify-between gap-4">
    <div>
        @if ($eyebrow)<p class="mb-1 text-xs font-bold uppercase tracking-widest text-brand-600">{{ $eyebrow }}</p>@endif
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h1>
        @if ($subtitle)<p class="mt-1 text-slate-500">{{ $subtitle }}</p>@endif
    </div>
    @if (trim((string) $slot) !== '')<div class="flex flex-wrap items-center gap-2">{{ $slot }}</div>@endif
</div>
