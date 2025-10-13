<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DistrictUpdateRequest extends FormRequest
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
            'kota_id' => 'sometimes|required|exists:master_kota,id',
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
            'kota_id.required' => 'ID kota wajib diisi.',
            'kota_id.exists' => 'ID kota yang dipilih tidak valid.',
            'nama.required' => 'Nama kecamatan wajib diisi.',
            'nama.string' => 'Nama kecamatan harus berupa teks.',
            'nama.max' => 'Nama kecamatan tidak boleh lebih dari 255 karakter.',
        ];
    }
}