<x-guest-layout>
    <x-slot name="title">Daftar</x-slot>

    <div x-data="{ show: false }">
        <h1 class="text-2xl font-extrabold tracking-tight">Buat akun Lapak ✨</h1>
        <p class="mt-1 text-sm text-slate-500">Gratis. Akun baru otomatis berperan <b>Pengguna</b> — role lain hanya bisa diberikan admin.</p>

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
            @csrf

            @php
                $input = fn (string $field) => 'w-full rounded-xl border bg-white px-4 py-2.5 outline-none transition focus:ring-4 '
                    . ($errors->has($field) ? 'border-rose-400 focus:ring-rose-100' : 'border-slate-200 focus:border-brand-400 focus:ring-brand-100');
            @endphp

            <div>
                <label for="name" class="mb-1.5 block text-sm font-semibold">Nama lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="{{ $input('name') }}">
                @error('name')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-sm font-semibold">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="{{ $input('email') }}">
                @error('email')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="password" class="mb-1.5 block text-sm font-semibold">Password</label>
                    <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" class="{{ $input('password') }}">
                    @error('password')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold">Ulangi password</label>
                    <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" class="{{ $input('password_confirmation') }}">
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-500">
                <input type="checkbox" x-model="show" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500"> Tampilkan password
            </label>

            <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-brand-600 to-fuchsia-600 py-3 font-bold text-white shadow-lg shadow-brand-500/30 transition hover:opacity-90">Daftar sekarang</button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:underline">Masuk</a>
        </p>
    </div>
</x-guest-layout>
