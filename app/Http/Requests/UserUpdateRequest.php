<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tipe_user === 'ADMIN' || $this->user()->id === $this->route('user')->id;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $email = $this->input('email');
            $nomor_telepon = $this->input('nomor_telepon');

            // Get current user data for comparison
            $currentUser = $this->route('user');
            $currentEmail = $currentUser->email;
            $currentNomorTelepon = $currentUser->nomor_telepon;

            // Validate that at least one of email or nomor_telepon must be provided
            // If both are being updated to empty, it's not allowed
            if ((empty($email) || $email === $currentEmail) &&
                (empty($nomor_telepon) || $nomor_telepon === $currentNomorTelepon) &&
                empty($currentEmail) &&
                empty($currentNomorTelepon)) {
                $validator->errors()->add('contact', 'Email atau nomor telepon wajib diisi salah satu.');
            }
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')->id;
        
        return [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($userId)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'nomor_telepon' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($userId)],
            'kata_sandi' => 'nullable|string|min:8|confirmed',
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
            'kata_sandi.min' => 'Kata sandi minimal 8 karakter.',
            'kata_sandi.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ];
    }
}