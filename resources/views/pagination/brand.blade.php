@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex flex-wrap items-center justify-center gap-1.5">
        @php
            $base = 'grid h-10 min-w-10 place-items-center rounded-xl px-3 text-sm font-semibold transition';
        @endphp

        @if ($paginator->onFirstPage())
            <span class="{{ $base }} cursor-not-allowed bg-slate-100 text-slate-300">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $base }} border border-slate-200 bg-white text-slate-600 hover:border-brand-300 hover:text-brand-700">‹</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="{{ $base }} text-slate-400">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="{{ $base }} bg-gradient-to-br from-brand-600 to-fuchsia-600 text-white shadow-lg shadow-brand-500/30">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="{{ $base }} border border-slate-200 bg-white text-slate-600 hover:border-brand-300 hover:text-brand-700">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $base }} border border-slate-200 bg-white text-slate-600 hover:border-brand-300 hover:text-brand-700">›</a>
        @else
            <span class="{{ $base }} cursor-not-allowed bg-slate-100 text-slate-300">›</span>
        @endif
    </nav>
@endif
