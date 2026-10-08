<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts.partials.head')
    <title>403 · Akses ditolak — Lapak</title>
</head>
<body class="grid min-h-screen place-items-center bg-slate-950 px-6 font-sans text-white antialiased"
      style="background-image: radial-gradient(50% 50% at 50% 0%, rgba(225,29,72,.35), transparent);">
    <div class="max-w-lg text-center">
        <div class="mx-auto mb-6 grid h-24 w-24 place-items-center rounded-3xl bg-rose-500/15 text-5xl ring-1 ring-rose-400/30">🛡️</div>
        <p class="text-sm font-bold uppercase tracking-[.3em] text-rose-400">Error 403</p>
        <h1 class="mt-2 text-4xl font-extrabold">Akses ditolak</h1>
        <p class="mt-3 text-slate-300">
            @php
                $message = $exception->getMessage();
                if ($message === '' || $message === 'This action is unauthorized.') {
                    $message = 'Kamu tidak memiliki izin untuk melakukan tindakan ini.';
                }
            @endphp
            {{ $message }}
        </p>

        @auth
            <p class="mt-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-sm">
                Masuk sebagai <b>{{ auth()->user()->name }}</b> · role <b>{{ auth()->user()->role_label }}</b>
            </p>
        @endauth

        <div class="mt-8 flex justify-center gap-3">
            <a href="{{ url()->previous() }}" class="rounded-full border border-white/20 px-5 py-2.5 text-sm font-semibold hover:bg-white/10">← Kembali</a>
            <a href="{{ url('/') }}" class="rounded-full bg-gradient-to-r from-brand-600 to-fuchsia-600 px-5 py-2.5 text-sm font-semibold">Ke beranda</a>
        </div>
    </div>
</body>
</html>
