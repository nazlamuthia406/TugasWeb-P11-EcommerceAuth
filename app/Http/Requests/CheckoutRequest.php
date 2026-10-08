<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'shipping_name'    => ['required', 'string', 'max:100'],
            'shipping_address' => ['required', 'string', 'min:10', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return ['shipping_name' => 'nama penerima', 'shipping_address' => 'alamat pengiriman'];
    }

    public function messages(): array
    {
        return [
            'required'    => ':attribute wajib diisi.',
            'min.string'  => ':attribute minimal :min karakter.',
            'max.string'  => ':attribute maksimal :max karakter.',
        ];
    }
}
