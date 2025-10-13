<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityUpdateRequest extends FormRequest
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
            'provinsi_id' => 'sometimes|required|exists:master_provinsi,id',
            'nama' => 'sometimes|required|string|max:255',
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
            'provinsi_id.required' => 'ID provinsi wajib diisi.',
            'provinsi_id.exists' => 'ID provinsi yang dipilih tidak valid.',
            'nama.required' => 'Nama kota wajib diisi.',
            'nama.string' => 'Nama kota harus berupa teks.',
            'nama.max' => 'Nama kota tidak boleh lebih dari 255 karakter.',
        ];
    }
}