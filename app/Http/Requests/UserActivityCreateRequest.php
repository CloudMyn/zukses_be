<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserActivityCreateRequest extends FormRequest
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
            'jenis_aktivitas' => 'required|string|max:100',
            'deskripsi_aktivitas' => 'nullable|string',
            'ip_address' => 'nullable|ip',
            'user_agent' => 'nullable|string|max:500',
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
            'jenis_aktivitas.required' => 'Jenis aktivitas wajib diisi',
            'jenis_aktivitas.string' => 'Jenis aktivitas harus berupa teks',
            'jenis_aktivitas.max' => 'Jenis aktivitas maksimal 100 karakter',
            'ip_address.ip' => 'IP address harus berupa format IP yang valid',
            'user_agent.string' => 'User agent harus berupa teks',
            'user_agent.max' => 'User agent maksimal 500 karakter',
            'metadata.json' => 'Metadata harus berupa format JSON yang valid',
        ];
    }
}