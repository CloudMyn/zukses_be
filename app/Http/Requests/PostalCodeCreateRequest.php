<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostalCodeCreateRequest extends FormRequest
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
            'kecamatan_id' => 'required|exists:master_kecamatan,id',
            'kode' => 'required|string|max:10',
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
            'kecamatan_id.required' => 'ID kecamatan wajib diisi.',
            'kecamatan_id.exists' => 'ID kecamatan yang dipilih tidak valid.',
            'kode.required' => 'Kode pos wajib diisi.',
            'kode.string' => 'Kode pos harus berupa teks.',
            'kode.max' => 'Kode pos tidak boleh lebih dari 10 karakter.',
        ];
    }
}