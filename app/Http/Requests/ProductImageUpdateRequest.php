<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductImageUpdateRequest extends FormRequest
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
            'id_produk' => 'sometimes|required|exists:tb_produk,id',
            'id_harga_varian' => 'nullable|exists:harga_varian_produk,id',
            'url_gambar' => 'sometimes|required|url|max:2048',
            'alt_text' => 'nullable|string|max:255',
            'urutan_gambar' => 'sometimes|required|integer|min:0',
            'is_gambar_utama' => 'boolean',
            'tipe_gambar' => 'nullable|string|max:50',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'id_produk.required' => 'ID produk wajib diisi.',
            'id_produk.exists' => 'ID produk tidak valid.',
            'url_gambar.required' => 'URL gambar wajib diisi.',
            'url_gambar.url' => 'URL gambar harus berupa URL yang valid.',
            'urutan_gambar.required' => 'Urutan gambar wajib diisi.',
            'urutan_gambar.integer' => 'Urutan gambar harus berupa angka.',
        ];
    }
}