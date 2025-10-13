<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductShippingInfoCreateRequest extends FormRequest
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
            'id_produk' => 'required|exists:tb_produk,id',
            'id_kota_asal' => 'required|integer|exists:master_kota,id',
            'nama_kota_asal' => 'required|string|max:255',
            'estimasi_pengiriman' => 'required|json',
            'berat_pengiriman' => 'required|numeric|min:0',
            'dimensi_pengiriman' => 'required|json',
            'biaya_pengemasan' => 'required|numeric|min:0',
            'is_gratis_ongkir' => 'boolean',
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
            'id_kota_asal.required' => 'ID kota asal wajib diisi.',
            'nama_kota_asal.required' => 'Nama kota asal wajib diisi.',
            'estimasi_pengiriman.required' => 'Estimasi pengiriman wajib diisi.',
            'berat_pengiriman.required' => 'Berat pengiriman wajib diisi.',
            'dimensi_pengiriman.required' => 'Dimensi pengiriman wajib diisi.',
            'biaya_pengemasan.required' => 'Biaya pengemasan wajib diisi.',
        ];
    }
}