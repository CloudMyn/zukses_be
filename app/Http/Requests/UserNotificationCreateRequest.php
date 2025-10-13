<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserNotificationCreateRequest extends FormRequest
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
            'id_user' => 'required|exists:users,id',
            'judul_notifikasi' => 'required|string|max:255',
            'isi_notifikasi' => 'required|string',
            'jenis_notifikasi' => 'required|in:ORDER,PROMO,SYSTEM,PAYMENT,PRODUCT',
            'metadata' => 'nullable|json',
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
            'id_user.required' => 'ID user wajib diisi',
            'id_user.exists' => 'User yang dipilih tidak valid',
            'judul_notifikasi.required' => 'Judul notifikasi wajib diisi',
            'judul_notifikasi.string' => 'Judul notifikasi harus berupa teks',
            'judul_notifikasi.max' => 'Judul notifikasi maksimal 255 karakter',
            'isi_notifikasi.required' => 'Isi notifikasi wajib diisi',
            'isi_notifikasi.string' => 'Isi notifikasi harus berupa teks',
            'jenis_notifikasi.required' => 'Jenis notifikasi wajib diisi',
            'jenis_notifikasi.in' => 'Jenis notifikasi tidak valid',
            'metadata.json' => 'Metadata harus berupa format JSON yang valid',
        ];
    }
}