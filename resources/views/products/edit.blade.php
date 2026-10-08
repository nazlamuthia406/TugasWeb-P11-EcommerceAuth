<x-app-layout>
    <x-slot name="title">Edit {{ $product->name }}</x-slot>

    <x-slot name="header">
        <x-page-header eyebrow="Penjual" title="Edit produk" :subtitle="$product->name" />
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        @include('products._form')
    </div>
</x-app-layout>
