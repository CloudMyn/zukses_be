<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductReviewCreateRequest extends FormRequest
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
            'id_produk' => 'required|exists:tb_produk,id',
            'id_order_item' => 'required|exists:tb_order_item,id',
            'id_varian_produk' => 'nullable|exists:varian_produk,id',
            'rating' => 'required|integer|min:1|max:5',
            'judul_ulasan' => 'required|string|max:255',
            'isi_ulasan' => 'required|string',
            'is_verified_purchase' => 'nullable|boolean',
            'is_rekomendasi' => 'nullable|boolean',
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
            'id_produk.required' => 'ID produk wajib diisi',
            'id_produk.exists' => 'Produk yang dipilih tidak valid',
            'id_order_item.required' => 'ID order item wajib diisi',
            'id_order_item.exists' => 'Order item yang dipilih tidak valid',
            'id_varian_produk.exists' => 'Varian produk yang dipilih tidak valid',
            'rating.required' => 'Rating wajib diisi',
            'rating.integer' => 'Rating harus berupa angka',
            'rating.min' => 'Rating minimal 1',
            'rating.max' => 'Rating maksimal 5',
            'judul_ulasan.required' => 'Judul ulasan wajib diisi',
            'judul_ulasan.string' => 'Judul ulasan harus berupa teks',
            'judul_ulasan.max' => 'Judul ulasan maksimal 255 karakter',
            'isi_ulasan.required' => 'Isi ulasan wajib diisi',
            'isi_ulasan.string' => 'Isi ulasan harus berupa teks',
        ];
    }
}