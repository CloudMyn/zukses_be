<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CartItemUpdateRequest extends FormRequest
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
            'id_cart' => 'sometimes|required|exists:keranjang_belanja,id',
            'id_produk' => 'sometimes|required|exists:tb_produk,id',
            'id_harga_varian' => 'nullable|exists:harga_varian_produk,id',
            'kuantitas' => 'sometimes|required|integer|min:1',
            'harga_satuan' => 'sometimes|required|numeric|min:0',
            'harga_total' => 'sometimes|required|numeric|min:0',
            'diskon_item' => 'sometimes|required|numeric|min:0',
            'catatan_item' => 'nullable|string|max:500',
            'gambar_produk' => 'nullable|string|max:2048',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'id_cart.required' => 'ID keranjang belanja wajib diisi.',
            'id_cart.exists' => 'ID keranjang belanja tidak valid.',
            'id_produk.required' => 'ID produk wajib diisi.',
            'id_produk.exists' => 'ID produk tidak valid.',
            'id_harga_varian.exists' => 'ID harga varian tidak valid.',
            'kuantitas.required' => 'Kuantitas wajib diisi.',
            'kuantitas.integer' => 'Kuantitas harus berupa angka.',
            'kuantitas.min' => 'Kuantitas minimal 1.',
            'harga_satuan.required' => 'Harga satuan wajib diisi.',
            'harga_satuan.numeric' => 'Harga satuan harus berupa angka.',
            'harga_satuan.min' => 'Harga satuan tidak boleh negatif.',
            'harga_total.required' => 'Harga total wajib diisi.',
            'harga_total.numeric' => 'Harga total harus berupa angka.',
            'harga_total.min' => 'Harga total tidak boleh negatif.',
            'diskon_item.required' => 'Diskon item wajib diisi.',
            'diskon_item.numeric' => 'Diskon item harus berupa angka.',
            'diskon_item.min' => 'Diskon item tidak boleh negatif.',
            'catatan_item.max' => 'Catatan item maksimal 500 karakter.',
            'gambar_produk.max' => 'URL gambar maksimal 2048 karakter.',
        ];
    }
}