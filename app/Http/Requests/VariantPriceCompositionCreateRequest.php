<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VariantPriceCompositionCreateRequest extends FormRequest
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
            'harga_varian_id' => 'required|exists:harga_varian_produk,id',
            'nilai_varian_id' => 'required|exists:nilai_varian_produk,id',
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
            'harga_varian_id.required' => 'ID harga varian wajib diisi.',
            'harga_varian_id.exists' => 'ID harga varian yang dipilih tidak valid.',
            'nilai_varian_id.required' => 'ID nilai varian wajib diisi.',
            'nilai_varian_id.exists' => 'ID nilai varian yang dipilih tidak valid.',
        ];
    }
}