<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartCreateRequest extends FormRequest
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
            'id_user' => 'required|exists:users,id',
            'session_id' => 'nullable|string|max:255',
            'id_seller' => 'required|exists:penjual,id',
            'total_items' => 'required|integer|min:0',
            'total_berat' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'total_diskon' => 'required|numeric|min:0',
            'is_cart_aktif' => 'boolean',
            'kadaluarsa_pada' => 'nullable|date',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'id_user.required' => 'ID pengguna wajib diisi.',
            'id_user.exists' => 'ID pengguna tidak valid.',
            'id_seller.required' => 'ID penjual wajib diisi.',
            'id_seller.exists' => 'ID penjual tidak valid.',
            'total_items.required' => 'Total item wajib diisi.',
            'total_berat.required' => 'Total berat wajib diisi.',
            'total_harga.required' => 'Total harga wajib diisi.',
            'total_diskon.required' => 'Total diskon wajib diisi.',
        ];
    }
}