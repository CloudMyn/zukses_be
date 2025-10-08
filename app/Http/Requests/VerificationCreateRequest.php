<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerificationCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jenis_verifikasi' => 'required|in:EMAIL,TELEPON,KTP,NPWP',
            'nilai_verifikasi' => 'required|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'jenis_verifikasi.required' => 'Jenis verifikasi wajib diisi.',
            'jenis_verifikasi.in' => 'Jenis verifikasi tidak valid.',
            'nilai_verifikasi.required' => 'Nilai verifikasi wajib diisi.',
        ];
    }
}