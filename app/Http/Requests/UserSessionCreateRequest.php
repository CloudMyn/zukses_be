<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSessionCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is typically handled during login
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|string|max:255|unique:sesi_pengguna',
            'id_user' => 'required|exists:users,id',
            'ip_address' => 'nullable|ip',
            'user_agent' => 'nullable|string|max:255',
            'payload' => 'required|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'id.required' => 'ID sesi wajib diisi.',
            'id.unique' => 'ID sesi sudah digunakan.',
            'id_user.required' => 'ID pengguna wajib diisi.',
            'id_user.exists' => 'Pengguna tidak ditemukan.',
        ];
    }
}
