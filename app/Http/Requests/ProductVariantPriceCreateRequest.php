<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductVariantPriceCreateRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'produk_id' => 'required|exists:produk,id',
            'gambar' => 'nullable|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'kode_varian' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'produk_id.required' => 'ID produk wajib diisi.',
            'produk_id.exists' => 'ID produk yang dipilih tidak valid.',
            'harga.required' => 'Harga varian wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh kurang dari 0.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
            'gambar.string' => 'Gambar harus berupa teks.',
            'gambar.max' => 'Gambar tidak boleh lebih dari 255 karakter.',
            'kode_varian.string' => 'Kode varian harus berupa teks.',
            'kode_varian.max' => 'Kode varian tidak boleh lebih dari 255 karakter.',
        ];
    }
}