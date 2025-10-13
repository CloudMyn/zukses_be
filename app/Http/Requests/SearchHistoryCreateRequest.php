<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchHistoryCreateRequest extends FormRequest
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
            'id_user' => 'nullable|exists:users,id',
            'kata_kunci_pencarian' => 'required|string|max:255',
            'jumlah_hasil' => 'nullable|integer|min:0',
            'ip_address' => 'nullable|ip',
            'user_agent' => 'nullable|string|max:500',
            'sumber_pencarian' => 'nullable|in:WEB,MOBILE_APP,MOBILE_WEB',
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
            'id_user.exists' => 'User yang dipilih tidak valid',
            'kata_kunci_pencarian.required' => 'Kata kunci pencarian wajib diisi',
            'kata_kunci_pencarian.string' => 'Kata kunci pencarian harus berupa teks',
            'kata_kunci_pencarian.max' => 'Kata kunci pencarian maksimal 255 karakter',
            'jumlah_hasil.integer' => 'Jumlah hasil harus berupa angka',
            'jumlah_hasil.min' => 'Jumlah hasil minimal 0',
            'ip_address.ip' => 'IP address harus berupa format IP yang valid',
            'user_agent.string' => 'User agent harus berupa teks',
            'user_agent.max' => 'User agent maksimal 500 karakter',
            'sumber_pencarian.in' => 'Sumber pencarian tidak valid',
            'metadata.json' => 'Metadata harus berupa format JSON yang valid',
        ];
    }
}