<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentLogCreateRequest extends FormRequest
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
            'id_transaksi_pembayaran' => 'required|exists:transaksi_pembayaran,id',
            'id_user' => 'required|exists:users,id',
            'aksi_log' => 'required|string|max:255',
            'deskripsi_log' => 'required|string',
            'data_sebelumnya' => 'nullable|json',
            'data_perubahan' => 'nullable|json',
            'ip_address' => 'nullable|ip',
            'user_agent' => 'nullable|string|max:500',
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
            'id_transaksi_pembayaran.required' => 'ID transaksi pembayaran wajib diisi',
            'id_transaksi_pembayaran.exists' => 'Transaksi pembayaran yang dipilih tidak valid',
            'id_user.required' => 'ID user wajib diisi',
            'id_user.exists' => 'User yang dipilih tidak valid',
            'aksi_log.required' => 'Aksi log wajib diisi',
            'aksi_log.string' => 'Aksi log harus berupa teks',
            'aksi_log.max' => 'Aksi log maksimal 255 karakter',
            'deskripsi_log.required' => 'Deskripsi log wajib diisi',
            'deskripsi_log.string' => 'Deskripsi log harus berupa teks',
            'data_sebelumnya.json' => 'Data sebelumnya harus berupa format JSON yang valid',
            'data_perubahan.json' => 'Data perubahan harus berupa format JSON yang valid',
            'ip_address.ip' => 'IP address harus berupa format IP yang valid',
            'user_agent.string' => 'User agent harus berupa teks',
            'user_agent.max' => 'User agent maksimal 500 karakter',
        ];
    }
}