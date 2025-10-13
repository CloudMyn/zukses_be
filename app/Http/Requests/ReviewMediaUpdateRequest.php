<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewMediaUpdateRequest extends FormRequest
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
        $id = $this->route('reviewMedia'); // Get the reviewMedia ID from the route

        return [
            'id_ulasan_produk' => 'sometimes|required|exists:ulasan_produk,id',
            'url_media' => 'sometimes|required|string|max:500',
            'jenis_media' => 'sometimes|required|in:IMAGE,VIDEO',
            'deskripsi_media' => 'nullable|string|max:500',
            'urutan_tampilan' => 'nullable|integer|min:0',
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
            'id_ulasan_produk.required' => 'ID ulasan produk wajib diisi',
            'id_ulasan_produk.exists' => 'Ulasan produk yang dipilih tidak valid',
            'url_media.required' => 'URL media wajib diisi',
            'url_media.string' => 'URL media harus berupa teks',
            'url_media.max' => 'URL media maksimal 500 karakter',
            'jenis_media.required' => 'Jenis media wajib diisi',
            'jenis_media.in' => 'Jenis media tidak valid',
            'urutan_tampilan.integer' => 'Urutan tampilan harus berupa angka',
            'urutan_tampilan.min' => 'Urutan tampilan minimal 0',
        ];
    }
}