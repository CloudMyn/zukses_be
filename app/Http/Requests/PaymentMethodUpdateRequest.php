<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodUpdateRequest extends FormRequest
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
            'nama_pembayaran' => 'sometimes|required|string|max:255',
            'tipe_pembayaran' => 'sometimes|required|in:TRANSFER_BANK,E_WALLET,VIRTUAL_ACCOUNT,CREDIT_CARD,COD,QRIS',
            'provider_pembayaran' => 'sometimes|required|string|max:255',
            'logo_pembayaran' => 'nullable|string|max:255',
            'deskripsi_pembayaran' => 'nullable|string',
            'biaya_admin_percent' => 'nullable|numeric|min:0',
            'biaya_admin_fixed' => 'nullable|numeric|min:0',
            'minimum_pembayaran' => 'nullable|numeric|min:0',
            'maksimum_pembayaran' => 'nullable|numeric|min:0',
            'is_aktif' => 'sometimes|required|boolean',
            'urutan_tampilan' => 'nullable|integer|min:0',
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
            'nama_pembayaran.required' => 'Nama pembayaran wajib diisi',
            'nama_pembayaran.string' => 'Nama pembayaran harus berupa teks',
            'nama_pembayaran.max' => 'Nama pembayaran maksimal 255 karakter',
            'tipe_pembayaran.required' => 'Tipe pembayaran wajib diisi',
            'tipe_pembayaran.in' => 'Tipe pembayaran tidak valid',
            'provider_pembayaran.required' => 'Provider pembayaran wajib diisi',
            'provider_pembayaran.string' => 'Provider pembayaran harus berupa teks',
            'provider_pembayaran.max' => 'Provider pembayaran maksimal 255 karakter',
            'biaya_admin_percent.numeric' => 'Biaya admin persen harus berupa angka',
            'biaya_admin_percent.min' => 'Biaya admin persen minimal 0',
            'biaya_admin_fixed.numeric' => 'Biaya admin tetap harus berupa angka',
            'biaya_admin_fixed.min' => 'Biaya admin tetap minimal 0',
            'minimum_pembayaran.numeric' => 'Minimum pembayaran harus berupa angka',
            'minimum_pembayaran.min' => 'Minimum pembayaran minimal 0',
            'maksimum_pembayaran.numeric' => 'Maksimum pembayaran harus berupa angka',
            'maksimum_pembayaran.min' => 'Maksimum pembayaran minimal 0',
            'is_aktif.required' => 'Status aktif wajib diisi',
            'is_aktif.boolean' => 'Status aktif harus berupa nilai boolean',
            'urutan_tampilan.integer' => 'Urutan tampilan harus berupa angka',
            'urutan_tampilan.min' => 'Urutan tampilan minimal 0',
        ];
    }
}