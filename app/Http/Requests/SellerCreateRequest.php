<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellerCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tipe_user === 'ADMIN' || $this->user()->tipe_user === 'PEDAGANG';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_user' => 'required|exists:users,id',
            'nama_toko' => 'required|string|max:255|unique:penjual',
            'slug_toko' => 'required|string|max:255|unique:penjual',
            'deskripsi_toko' => 'nullable|string',
            'logo_toko' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_toko' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nomor_ktp' => 'required|string|max:16|unique:penjual',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nomor_npwp' => 'nullable|string|max:15|unique:penjual',
            'foto_npwp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jenis_usaha' => 'required|in:INDIVIDU,PERUSAHAAN',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'nama_toko.required' => 'Nama toko wajib diisi.',
            'nama_toko.unique' => 'Nama toko sudah digunakan.',
            'slug_toko.required' => 'Slug toko wajib diisi.',
            'slug_toko.unique' => 'Slug toko sudah digunakan.',
            'nomor_ktp.required' => 'Nomor KTP wajib diisi.',
            'nomor_ktp.unique' => 'Nomor KTP sudah digunakan.',
            'nomor_npwp.unique' => 'Nomor NPWP sudah digunakan.',
        ];
    }
}
