<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts.partials.head')
    <title>404 · Tidak ditemukan — Lapak</title>
</head>
<body class="grid min-h-screen place-items-center bg-slate-950 px-6 font-sans text-white antialiased"
      style="background-image: radial-gradient(50% 50% at 50% 0%, rgba(124,58,237,.45), transparent);">
    <div class="max-w-lg text-center">
        <div class="mx-auto mb-6 grid h-24 w-24 place-items-center rounded-3xl bg-brand-500/15 text-5xl ring-1 ring-brand-400/30">🧭</div>
        <p class="text-sm font-bold uppercase tracking-[.3em] text-brand-300">Error 404</p>
        <h1 class="mt-2 text-4xl font-extrabold">Halaman tidak ditemukan</h1>
        <p class="mt-3 text-slate-300">Produk atau halaman yang kamu cari mungkin sudah dihapus atau alamatnya salah.</p>
        <div class="mt-8 flex justify-center gap-3">
            <a href="{{ url('/products') }}" class="rounded-full border border-white/20 px-5 py-2.5 text-sm font-semibold hover:bg-white/10">Lihat katalog</a>
            <a href="{{ url('/') }}" class="rounded-full bg-gradient-to-r from-brand-600 to-fuchsia-600 px-5 py-2.5 text-sm font-semibold">Ke beranda</a>
        </div>
    </div>
</body>
</html>
