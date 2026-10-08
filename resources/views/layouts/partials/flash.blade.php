@if (session('success') || session('error') || $errors->any())
    <div class="pointer-events-none fixed right-4 top-20 z-50 w-full max-w-sm space-y-3"
         x-data="{ show: true }" x-init="setTimeout(() => show = false, 6500)"
         x-show="show" x-cloak x-transition.opacity.duration.300ms>

        @if (session('success'))
            <div class="pointer-events-auto flex items-start gap-3 rounded-2xl border border-emerald-200 bg-white p-4 shadow-xl shadow-emerald-100">
                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-emerald-100 text-emerald-600">✓</span>
                <p class="flex-1 text-sm font-medium text-slate-700">{{ session('success') }}</p>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600" aria-label="Tutup">✕</button>
            </div>
        @endif

        @if (session('error'))
            <div class="pointer-events-auto flex items-start gap-3 rounded-2xl border border-rose-200 bg-white p-4 shadow-xl shadow-rose-100">
                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-rose-100 text-rose-600">!</span>
                <p class="flex-1 text-sm font-medium text-slate-700">{{ session('error') }}</p>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600" aria-label="Tutup">✕</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="pointer-events-auto flex items-start gap-3 rounded-2xl border border-rose-200 bg-white p-4 shadow-xl shadow-rose-100">
                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-rose-100 text-rose-600">!</span>
                <div class="flex-1 text-sm">
                    <p class="font-semibold text-slate-800">Periksa kembali isian kamu</p>
                    <p class="text-slate-500">Ada {{ $errors->count() }} kesalahan pada form.</p>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600" aria-label="Tutup">✕</button>
            </div>
        @endif
    </div>
@endif
