<footer class="mt-16 bg-slate-950 text-slate-300">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-4 lg:px-8">
        <div class="md:col-span-2">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-extrabold text-white">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-fuchsia-500">🛍️</span> Lapak
            </a>
            <p class="mt-4 max-w-sm text-sm text-slate-400">Marketplace mini dibuat dengan Laravel untuk Tugas Rutin 11 — Pemrograman Web. Database relasional, autentikasi Breeze, multi-role, dan policy.</p>
        </div>
        <div>
            <h4 class="mb-3 text-sm font-bold text-white">Jelajahi</h4>
            <ul class="space-y-2 text-sm">
                <li><a class="hover:text-white" href="{{ route('products.index') }}">Katalog Produk</a></li>
                <li><a class="hover:text-white" href="{{ route('products.index', ['sort' => 'price_asc']) }}">Termurah</a></li>
                <li><a class="hover:text-white" href="{{ route('products.index', ['sort' => 'latest']) }}">Terbaru</a></li>
            </ul>
        </div>
        <div>
            <h4 class="mb-3 text-sm font-bold text-white">Akun</h4>
            <ul class="space-y-2 text-sm">
                @auth
                    <li><a class="hover:text-white" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a class="hover:text-white" href="{{ route('orders.index') }}">Pesanan Saya</a></li>
                    <li><a class="hover:text-white" href="{{ route('products.manage') }}">Kelola Produk</a></li>
                @else
                    <li><a class="hover:text-white" href="{{ route('login') }}">Masuk</a></li>
                    <li><a class="hover:text-white" href="{{ route('register') }}">Daftar</a></li>
                @endauth
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10 py-5 text-center text-xs text-slate-500">
        © {{ date('Y') }} Lapak · Laravel {{ app()->version() }} · PHP {{ PHP_VERSION }}
    </div>
</footer>
