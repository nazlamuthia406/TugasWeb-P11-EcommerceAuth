<x-app-layout>
    <x-slot name="title">Tambah Produk</x-slot>

    <x-slot name="header">
        <x-page-header eyebrow="Penjual" title="Tambah produk baru" subtitle="Isi detail produk agar pembeli mudah menemukannya." />
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        @include('products._form')
    </div>
</x-app-layout>
