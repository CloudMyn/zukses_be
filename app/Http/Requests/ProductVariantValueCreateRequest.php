<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductVariantValueCreateRequest extends FormRequest
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
            'varian_id' => 'required|exists:varian_produk,id',
            'nilai' => 'required|string|max:255',
            'urutan' => 'nullable|integer|min:0',
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
            'varian_id.required' => 'ID varian produk wajib diisi.',
            'varian_id.exists' => 'ID varian produk yang dipilih tidak valid.',
            'nilai.required' => 'Nilai varian wajib diisi.',
            'nilai.string' => 'Nilai varian harus berupa teks.',
            'nilai.max' => 'Nilai varian tidak boleh lebih dari 255 karakter.',
            'urutan.integer' => 'Urutan harus berupa angka.',
            'urutan.min' => 'Urutan tidak boleh kurang dari 0.',
        ];
    }
}