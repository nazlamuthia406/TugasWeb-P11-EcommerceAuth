{{--
    Form bersama create & edit.
    Variabel: $product (new Product saat create), $categories, $tags
--}}
@php
    $editing    = $product->exists;
    $selected   = collect(old('tags', $editing ? $product->tags->pluck('id')->all() : []))->map(fn ($id) => (int) $id)->all();
    $field      = fn (string $name) => 'w-full rounded-xl border bg-white px-4 py-2.5 outline-none transition focus:ring-4 '
        . ($errors->has($name) ? 'border-rose-400 focus:ring-rose-100' : 'border-slate-200 focus:border-brand-400 focus:ring-brand-100');
@endphp

<form method="POST" action="{{ $editing ? route('products.update', $product) : route('products.store') }}"
      x-data="{ emoji: @js(old('emoji', $product->emoji ?: '📦')) }"
      class="grid gap-8 lg:grid-cols-[1fr_20rem]">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    {{-- Kolom kiri: isian --}}
    <div class="space-y-6 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8">
        <div>
            <label for="name" class="mb-1.5 block text-sm font-semibold">Nama produk</label>
            <input id="name" name="name" type="text" maxlength="150" value="{{ old('name', $product->name) }}" required class="{{ $field('name') }}" placeholder="Contoh: Laptop ASUS Vivobook 14">
            @error('name')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="category_id" class="mb-1.5 block text-sm font-semibold">Kategori</label>
                <select id="category_id" name="category_id" required class="{{ $field('category_id') }}">
                    <option value="">— Pilih kategori —</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) old('category_id', $product->category_id) === $category->id)>{{ $category->icon }} {{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="price" class="mb-1.5 block text-sm font-semibold">Harga (Rp)</label>
                <input id="price" name="price" type="number" min="0" step="1" value="{{ old('price', $editing ? (int) $product->price : '') }}" required class="{{ $field('price') }}">
                @error('price')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="stock" class="mb-1.5 block text-sm font-semibold">Stok</label>
                <input id="stock" name="stock" type="number" min="0" step="1" value="{{ old('stock', $product->stock ?? 0) }}" required class="{{ $field('stock') }}">
                @error('stock')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="emoji" class="mb-1.5 block text-sm font-semibold">Ikon produk</label>
                <input id="emoji" name="emoji" type="text" maxlength="8" x-model="emoji" class="{{ $field('emoji') }}">
                @error('emoji')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="-mt-2 flex flex-wrap gap-2">
            @foreach (['💻', '📱', '🎧', '👕', '👟', '🎒', '🏠', '⚽', '📚', '💄', '☕', '🎁'] as $e)
                <button type="button" @click="emoji = '{{ $e }}'" class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 text-xl transition hover:bg-brand-100">{{ $e }}</button>
            @endforeach
        </div>

        <div>
            <label for="description" class="mb-1.5 block text-sm font-semibold">Deskripsi</label>
            <textarea id="description" name="description" rows="6" required class="{{ $field('description') }}" placeholder="Jelaskan produkmu: bahan, ukuran, kelebihan…">{{ old('description', $product->description) }}</textarea>
            @error('description')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <p class="mb-2 text-sm font-semibold">Tag</p>
            <div class="flex flex-wrap gap-2">
                @foreach ($tags as $tag)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="peer sr-only" @checked(in_array($tag->id, $selected, true))>
                        <span class="inline-block rounded-full px-3.5 py-1.5 text-sm font-medium text-slate-600 ring-1 ring-inset ring-slate-200 transition peer-checked:bg-brand-600 peer-checked:text-white peer-checked:ring-brand-600 peer-focus-visible:ring-2 hover:bg-slate-50">#{{ $tag->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('tags.*')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        @can('manage-all-products')
            <label class="flex items-start gap-3 rounded-2xl bg-amber-50 p-4 ring-1 ring-amber-200">
                <input type="checkbox" name="is_featured" value="1" class="mt-1 rounded border-amber-300 text-brand-600 focus:ring-brand-500" @checked(old('is_featured', $product->is_featured))>
                <span>
                    <span class="block text-sm font-semibold text-amber-900">⭐ Tampilkan sebagai produk pilihan</span>
                    <span class="block text-xs text-amber-700">Hanya admin dan editor yang dapat mengubah pengaturan ini.</span>
                </span>
            </label>
        @endcan
    </div>

    {{-- Kolom kanan: pratinjau + tombol --}}
    <aside class="space-y-4 lg:sticky lg:top-24 lg:self-start">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white">
            <div class="grid h-40 place-items-center bg-gradient-to-br from-brand-500 to-fuchsia-500">
                <span class="text-7xl drop-shadow-lg" x-text="emoji || '📦'"></span>
            </div>
            <div class="p-4 text-sm text-slate-500">Pratinjau ikon produk. Warna kartu mengikuti kategori yang kamu pilih.</div>
        </div>

        <button type="submit" class="w-full rounded-full bg-gradient-to-r from-brand-600 to-fuchsia-600 py-3.5 font-bold text-white shadow-xl shadow-brand-500/30 transition hover:opacity-90">
            {{ $editing ? 'Simpan perubahan' : 'Terbitkan produk' }}
        </button>
        <a href="{{ $editing ? route('products.show', $product) : route('products.manage') }}" class="block rounded-full border border-slate-200 bg-white py-3 text-center text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</a>
    </aside>
</form>
