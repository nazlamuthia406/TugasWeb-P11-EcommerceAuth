<x-guest-layout>
    <x-slot name="title">Masuk</x-slot>

    <div x-data="{ email: @js(old('email', '')), password: '', show: false }">
        <h1 class="text-2xl font-extrabold tracking-tight">Selamat datang kembali 👋</h1>
        <p class="mt-1 text-sm text-slate-500">Masuk untuk belanja, menjual, dan mengelola pesananmu.</p>

        @if (session('status'))
            <div class="mt-5 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-sm font-semibold">Email</label>
                <input id="email" type="email" name="email" x-model="email" required autofocus autocomplete="username"
                       class="w-full rounded-xl border bg-white px-4 py-2.5 outline-none transition focus:ring-4 {{ $errors->has('email') ? 'border-rose-400 focus:ring-rose-100' : 'border-slate-200 focus:border-brand-400 focus:ring-brand-100' }}">
                @error('email')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <div class="mb-1.5 flex items-center justify-between">
                    <label for="password" class="text-sm font-semibold">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-brand-600 hover:underline">Lupa password?</a>
                    @endif
                </div>
                <div class="relative">
                    <input id="password" :type="show ? 'text' : 'password'" name="password" x-model="password" required autocomplete="current-password"
                           class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 pr-16 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-400 hover:text-slate-600" x-text="show ? 'Sembunyi' : 'Lihat'"></button>
                </div>
                @error('password')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                Ingat saya
            </label>

            <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-brand-600 to-fuchsia-600 py-3 font-bold text-white shadow-lg shadow-brand-500/30 transition hover:opacity-90">Masuk</button>
        </form>

        {{-- Akun demo: hanya tampil di environment local, untuk menguji 3 role --}}
        @if (app()->environment('local'))
            <div class="mt-7 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4">
                <p class="mb-3 text-xs font-bold uppercase tracking-widest text-slate-400">Akun demo · klik untuk mengisi</p>
                <div class="grid grid-cols-3 gap-2 text-xs font-semibold">
                    <button type="button" @click="email = 'admin@example.com'; password = 'password'" class="rounded-lg bg-rose-50 px-2 py-2 text-rose-700 ring-1 ring-rose-200 hover:bg-rose-100">🛡️ Admin</button>
                    <button type="button" @click="email = 'editor@example.com'; password = 'password'" class="rounded-lg bg-sky-50 px-2 py-2 text-sky-700 ring-1 ring-sky-200 hover:bg-sky-100">✏️ Editor</button>
                    <button type="button" @click="email = 'user@example.com'; password = 'password'" class="rounded-lg bg-slate-100 px-2 py-2 text-slate-700 ring-1 ring-slate-200 hover:bg-slate-200">👤 User</button>
                </div>
            </div>
        @endif

        <p class="mt-6 text-center text-sm text-slate-500">
            Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:underline">Daftar gratis</a>
        </p>
    </div>
</x-guest-layout>
