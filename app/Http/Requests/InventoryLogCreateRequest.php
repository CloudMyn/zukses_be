<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryLogCreateRequest extends FormRequest
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
            'id_harga_varian' => 'nullable|exists:harga_varian_produk,id',
            'tipe_transaksi' => 'required|in:MASUK,KELUAR,MASUK_KOREKSI,KELUAR_KOREKSI',
            'jumlah_transaksi' => 'required|integer',
            'stok_sebelum' => 'required|integer',
            'stok_sesudah' => 'required|integer',
            'alasan_transaksi' => 'required|string|max:255',
            'id_operator' => 'required|exists:users,id',
            'catatan_tambahan' => 'nullable|string',
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
            'tipe_transaksi.required' => 'Tipe transaksi wajib diisi.',
            'jumlah_transaksi.required' => 'Jumlah transaksi wajib diisi.',
            'stok_sebelum.required' => 'Stok sebelum transaksi wajib diisi.',
            'stok_sesudah.required' => 'Stok setelah transaksi wajib diisi.',
            'alasan_transaksi.required' => 'Alasan transaksi wajib diisi.',
            'id_operator.required' => 'ID operator wajib diisi.',
            'id_operator.exists' => 'ID operator tidak valid.',
        ];
    }
}