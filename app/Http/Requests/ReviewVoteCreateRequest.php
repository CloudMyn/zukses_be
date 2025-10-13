<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewVoteCreateRequest extends FormRequest
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
            'id_ulasan_produk' => 'required|exists:ulasan_produk,id',
            'jenis_vote' => 'required|in:SUKA,TIDAK_SUKA',
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
            'jenis_vote.required' => 'Jenis vote wajib diisi',
            'jenis_vote.in' => 'Jenis vote tidak valid',
        ];
    }
}