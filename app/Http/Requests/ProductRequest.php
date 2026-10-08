<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // otorisasi dilakukan di controller lewat ProductPolicy
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price'       => ['required', 'numeric', 'min:0', 'max:999999999'],
            'stock'       => ['required', 'integer', 'min:0', 'max:100000'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'emoji'       => ['nullable', 'string', 'max:8'],
            'tags'        => ['nullable', 'array'],
            'tags.*'      => ['integer', 'exists:tags,id'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama produk', 'category_id' => 'kategori', 'price' => 'harga',
            'stock' => 'stok', 'description' => 'deskripsi', 'emoji' => 'emoji',
        ];
    }

    public function messages(): array
    {
        return [
            'required'        => ':attribute wajib diisi.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'numeric'         => ':attribute harus berupa angka.',
            'integer'         => ':attribute harus berupa bilangan bulat.',
            'min.numeric'     => ':attribute tidak boleh kurang dari :min.',
            'min.string'      => ':attribute minimal :min karakter.',
            'max.string'      => ':attribute maksimal :max karakter.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Emoji kosong -> pakai default; rapikan spasi pada nama
        $this->merge([
            'name'  => is_string($this->name) ? trim($this->name) : $this->name,
            'emoji' => $this->emoji ?: '📦',
        ]);
    }
}
