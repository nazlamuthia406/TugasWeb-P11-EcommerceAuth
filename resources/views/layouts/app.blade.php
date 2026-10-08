<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    @include('layouts.partials.head')
    <title>{{ isset($title) ? $title . ' · ' : '' }}Lapak</title>
</head>
<body class="flex min-h-screen flex-col bg-slate-50 font-sans text-slate-800 antialiased">
    @include('layouts.partials.navbar')
    @include('layouts.partials.flash')

    @isset($header)
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8">{{ $header }}</div>
        </header>
    @endisset

    <main class="flex-1">{{ $slot }}</main>

    @include('layouts.partials.footer')
</body>
</html>
