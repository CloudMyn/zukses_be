<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VariantShippingInfoUpdateRequest extends FormRequest
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
            'harga_varian_id' => 'sometimes|required|exists:harga_varian_produk,id',
            'berat' => 'sometimes|required|numeric|min:0',
            'panjang' => 'sometimes|required|numeric|min:0',
            'lebar' => 'sometimes|required|numeric|min:0',
            'tinggi' => 'sometimes|required|numeric|min:0',
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
            'berat.required' => 'Berat wajib diisi.',
            'berat.numeric' => 'Berat harus berupa angka.',
            'berat.min' => 'Berat tidak boleh kurang dari 0.',
            'panjang.required' => 'Panjang wajib diisi.',
            'panjang.numeric' => 'Panjang harus berupa angka.',
            'panjang.min' => 'Panjang tidak boleh kurang dari 0.',
            'lebar.required' => 'Lebar wajib diisi.',
            'lebar.numeric' => 'Lebar harus berupa angka.',
            'lebar.min' => 'Lebar tidak boleh kurang dari 0.',
            'tinggi.required' => 'Tinggi wajib diisi.',
            'tinggi.numeric' => 'Tinggi harus berupa angka.',
            'tinggi.min' => 'Tinggi tidak boleh kurang dari 0.',
        ];
    }
}