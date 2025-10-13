<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellerShippingMethodCreateRequest extends FormRequest
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
            'id_seller' => 'required|exists:penjual,id',
            'id_metode_pengiriman' => 'required|exists:metode_pengiriman,id',
            'is_aktif' => 'boolean',
            'biaya_tambahan' => 'required|numeric|min:0',
            'estimasi_pengiriman' => 'required|string|max:100',
            'catatan_pengiriman' => 'nullable|string|max:500',
            'konfigurasi_metode' => 'nullable|json',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'id_seller.required' => 'ID penjual wajib diisi.',
            'id_seller.exists' => 'ID penjual tidak valid.',
            'id_metode_pengiriman.required' => 'ID metode pengiriman wajib diisi.',
            'id_metode_pengiriman.exists' => 'ID metode pengiriman tidak valid.',
            'biaya_tambahan.required' => 'Biaya tambahan wajib diisi.',
            'biaya_tambahan.numeric' => 'Biaya tambahan harus berupa angka.',
            'biaya_tambahan.min' => 'Biaya tambahan tidak boleh negatif.',
            'estimasi_pengiriman.required' => 'Estimasi pengiriman wajib diisi.',
            'estimasi_pengiriman.string' => 'Estimasi pengiriman harus berupa teks.',
            'estimasi_pengiriman.max' => 'Estimasi pengiriman maksimal 100 karakter.',
            'catatan_pengiriman.max' => 'Catatan pengiriman maksimal 500 karakter.',
            'konfigurasi_metode.json' => 'Konfigurasi metode harus dalam format JSON.',
        ];
    }
}