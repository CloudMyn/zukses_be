<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductVariantUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'produk_id' => 'sometimes|required|exists:tb_produk,id',
            'nama_varian' => 'sometimes|required|string|max:255',
            'urutan' => 'sometimes|required|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'produk_id.required' => 'ID produk wajib diisi.',
            'produk_id.exists' => 'ID produk tidak valid.',
            'nama_varian.required' => 'Nama varian wajib diisi.',
            'urutan.required' => 'Urutan wajib diisi.',
            'urutan.integer' => 'Urutan harus berupa angka.',
        ];
    }
}