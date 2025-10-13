<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderUpdateRequest extends FormRequest
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
            'nomor_pesanan' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('pesanan', 'nomor_pesanan')->ignore($this->route('order')),
            ],
            'id_customer' => 'sometimes|required|exists:users,id',
            'id_alamat_pengiriman' => 'sometimes|required|exists:alamat,id',
            'status_pesanan' => 'sometimes|required|in:MENUNGGU_PEMBAYARAN,DIBAYAR,DIKEMAS,DIKIRIM,SELESAI,BATAL,DIKEMBALIKAN',
            'status_pembayaran' => 'sometimes|required|in:BELUM_DIBAYAR,SUDAH_DIBAYAR,KADALUARSA,DIBATALKAN',
            'total_items' => 'sometimes|required|integer|min:1',
            'total_berat' => 'sometimes|required|numeric|min:0.01',
            'subtotal_produk' => 'sometimes|required|numeric|min:0',
            'total_diskon_produk' => 'sometimes|required|numeric|min:0',
            'total_ongkir' => 'sometimes|required|numeric|min:0',
            'total_biaya_layanan' => 'sometimes|required|numeric|min:0',
            'total_pajak' => 'sometimes|required|numeric|min:0',
            'total_pembayaran' => 'sometimes|required|numeric|min:1000',
            'metode_pembayaran' => 'sometimes|required|in:TRANSFER_BANK,GOPAY,OVO,DANA,CASH_ON_DELIVERY',
            'bank_pembayaran' => 'nullable|string|max:50',
            'va_number' => 'nullable|string|max:50',
            'deadline_pembayaran' => 'nullable|date',
            'tanggal_dibayar' => 'nullable|date',
            'no_resi' => 'nullable|string|max:100',
            'catatan_pesanan' => 'nullable|string|max:500',
            'tanggal_pengiriman' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'tanggal_dibatalkan' => 'nullable|date',
            'alasan_pembatalan' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'nomor_pesanan.required' => 'Nomor pesanan wajib diisi.',
            'nomor_pesanan.unique' => 'Nomor pesanan sudah digunakan.',
            'id_customer.required' => 'ID customer wajib diisi.',
            'id_customer.exists' => 'Customer tidak valid.',
            'id_alamat_pengiriman.required' => 'Alamat pengiriman wajib diisi.',
            'id_alamat_pengiriman.exists' => 'Alamat pengiriman tidak valid.',
            'status_pesanan.required' => 'Status pesanan wajib diisi.',
            'status_pembayaran.required' => 'Status pembayaran wajib diisi.',
            'total_items.required' => 'Total items wajib diisi.',
            'total_berat.required' => 'Total berat wajib diisi.',
            'subtotal_produk.required' => 'Subtotal produk wajib diisi.',
            'total_diskon_produk.required' => 'Total diskon produk wajib diisi.',
            'total_ongkir.required' => 'Total ongkos kirim wajib diisi.',
            'total_biaya_layanan.required' => 'Total biaya layanan wajib diisi.',
            'total_pajak.required' => 'Total pajak wajib diisi.',
            'total_pembayaran.required' => 'Total pembayaran wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib diisi.',
            'total_pembayaran.min' => 'Total pembayaran minimal Rp 1.000.',
        ];
    }
}