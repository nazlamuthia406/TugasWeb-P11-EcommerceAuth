<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
    <title>{{ isset($title) ? $title . ' · ' : '' }}Lapak</title>
</head>
<body class="font-sans text-slate-800 antialiased">
<div class="grid min-h-screen lg:grid-cols-2">

    {{-- Panel brand (kiri) --}}
    <aside class="relative hidden overflow-hidden bg-slate-950 p-12 text-white lg:flex lg:flex-col lg:justify-between"
           style="background-image: radial-gradient(60% 50% at 15% 10%, rgba(124,58,237,.55), transparent), radial-gradient(50% 45% at 95% 30%, rgba(217,70,239,.40), transparent), radial-gradient(45% 40% at 40% 100%, rgba(6,182,212,.30), transparent);">
        <a href="{{ route('home') }}" class="flex items-center gap-3 text-xl font-extrabold">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-fuchsia-500 shadow-lg shadow-brand-500/40">🛍️</span>
            Lapak
        </a>

        <div class="relative">
            <div class="pointer-events-none absolute -right-4 -top-40 hidden text-[10rem] opacity-90 xl:block" style="filter: drop-shadow(0 20px 30px rgba(0,0,0,.35)); transform: rotate(12deg);">🎧</div>
            <div class="pointer-events-none absolute -left-6 -top-24 hidden text-8xl xl:block" style="filter: drop-shadow(0 20px 30px rgba(0,0,0,.35)); transform: rotate(-14deg);">👟</div>
            <h2 class="max-w-md text-4xl font-extrabold leading-tight">
                Belanja cerdas,<br>
                <span class="bg-gradient-to-r from-brand-300 to-fuchsia-300 bg-clip-text text-transparent">jual dengan mudah.</span>
            </h2>
            <p class="mt-4 max-w-md text-slate-300">Marketplace mini untuk mahasiswa: ratusan produk, tiga peran pengguna, dan keamanan yang dijaga Laravel.</p>

            <dl class="mt-10 grid max-w-md grid-cols-3 gap-4">
                @foreach ([['56+', 'Produk'], ['3', 'Role'], ['100%', 'CSRF aman']] as [$n, $l])
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                        <dt class="text-2xl font-extrabold">{{ $n }}</dt>
                        <dd class="text-xs text-slate-300">{{ $l }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>

        <p class="text-xs text-slate-400">Tugas Rutin 11 — Pemrograman Web · Laravel {{ app()->version() }}</p>
    </aside>

    {{-- Form (kanan) --}}
    <section class="flex items-center justify-center bg-slate-50 px-6 py-12">
        <div class="w-full max-w-md">
            <a href="{{ route('home') }}" class="mb-8 flex items-center gap-2 text-lg font-extrabold lg:hidden">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-fuchsia-500 text-white">🛍️</span> Lapak
            </a>
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60">
                {{ $slot }}
            </div>
        </div>
    </section>
</div>
</body>
</html>
