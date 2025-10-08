<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tipe_user === 'ADMIN';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'nomor_telepon' => 'nullable|string|max:20|unique:users',
            'kata_sandi' => 'required|string|min:8|confirmed',
            'tipe_user' => 'required|in:ADMIN,PELANGGAN,PEDAGANG',
            'status' => 'required|in:AKTIF,TIDAK_AKTIF,DIBLOKIR,SUSPEND',
            'nama_depan' => 'nullable|string|max:255',
            'nama_belakang' => 'nullable|string|max:255',
            'nama_lengkap' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:LAKI_LAKI,PEREMPUAN,RAHASIA',
            'tanggal_lahir' => 'nullable|date',
            'bio' => 'nullable|string',
            'pengaturan' => 'nullable|json',
            'url_media_sosial' => 'nullable|json',
            'bidang_interests' => 'nullable|json',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'kata_sandi.required' => 'Kata sandi wajib diisi.',
            'kata_sandi.min' => 'Kata sandi minimal 8 karakter.',
            'kata_sandi.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ];
    }
}