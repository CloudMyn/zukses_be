<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerificationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $verification = $this->route('verification');
        return $this->user()->tipe_user === 'ADMIN' || $this->user()->id === $verification->id_user;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_verifikasi' => 'required|string|size:6',
            'telah_digunakan' => 'boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'kode_verifikasi.required' => 'Kode verifikasi wajib diisi.',
            'kode_verifikasi.size' => 'Kode verifikasi harus 6 karakter.',
        ];
    }
}