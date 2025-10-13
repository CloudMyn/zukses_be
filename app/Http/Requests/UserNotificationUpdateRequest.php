<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserNotificationUpdateRequest extends FormRequest
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
        $id = $this->route('userNotification'); // Get the userNotification ID from the route

        return [
            'id_user' => 'sometimes|required|exists:users,id',
            'judul_notifikasi' => 'sometimes|required|string|max:255',
            'isi_notifikasi' => 'sometimes|required|string',
            'jenis_notifikasi' => 'sometimes|required|in:ORDER,PROMO,SYSTEM,PAYMENT,PRODUCT',
            'status_pembacaan' => 'sometimes|required|in:DIBACA,BELUM_DIBACA',
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
            'status_pembacaan.in' => 'Status pembacaan tidak valid',
            'metadata.json' => 'Metadata harus berupa format JSON yang valid',
        ];
    }
}