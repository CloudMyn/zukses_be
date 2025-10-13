<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentTransactionCreateRequest extends FormRequest
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
            'id_pesanan' => 'required|exists:pesanan,id',
            'id_metode_pembayaran' => 'required|exists:metode_pembayaran,id',
            'reference_id' => 'required|string|max:255|unique:transaksi_pembayaran,reference_id',
            'jumlah_pembayaran' => 'required|numeric|min:0',
            'status_transaksi' => 'required|in:MENUNGGU,BERHASIL,GAGAL,KADALUARSA',
            'channel_pembayaran' => 'nullable|string|max:255',
            'va_number' => 'nullable|string|max:255',
            'qr_code' => 'nullable|url',
            'deep_link' => 'nullable|url',
            'tanggal_kadaluarsa' => 'nullable|date',
            'tanggal_bayar' => 'nullable|date',
            'response_gateway' => 'nullable|json',
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
            'id_pesanan.required' => 'ID pesanan wajib diisi',
            'id_pesanan.exists' => 'Pesanan yang dipilih tidak valid',
            'id_metode_pembayaran.required' => 'Metode pembayaran wajib diisi',
            'id_metode_pembayaran.exists' => 'Metode pembayaran yang dipilih tidak valid',
            'reference_id.required' => 'Reference ID wajib diisi',
            'reference_id.string' => 'Reference ID harus berupa teks',
            'reference_id.max' => 'Reference ID maksimal 255 karakter',
            'reference_id.unique' => 'Reference ID sudah digunakan',
            'jumlah_pembayaran.required' => 'Jumlah pembayaran wajib diisi',
            'jumlah_pembayaran.numeric' => 'Jumlah pembayaran harus berupa angka',
            'jumlah_pembayaran.min' => 'Jumlah pembayaran minimal 0',
            'status_transaksi.required' => 'Status transaksi wajib diisi',
            'status_transaksi.in' => 'Status transaksi tidak valid',
            'channel_pembayaran.string' => 'Channel pembayaran harus berupa teks',
            'channel_pembayaran.max' => 'Channel pembayaran maksimal 255 karakter',
            'va_number.string' => 'VA number harus berupa teks',
            'va_number.max' => 'VA number maksimal 255 karakter',
            'qr_code.url' => 'QR Code harus berupa URL yang valid',
            'deep_link.url' => 'Deep link harus berupa URL yang valid',
            'tanggal_kadaluarsa.date' => 'Tanggal kadaluarsa harus berupa format tanggal yang valid',
            'tanggal_bayar.date' => 'Tanggal bayar harus berupa format tanggal yang valid',
            'response_gateway.json' => 'Response gateway harus berupa format JSON yang valid',
        ];
    }
}