<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemSettingUpdateRequest extends FormRequest
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
        $id = $this->route('systemSetting'); // Get the systemSetting ID from the route

        return [
            'nama_pengaturan' => 'sometimes|required|string|max:255|unique:pengaturan_sistem,nama_pengaturan,' . $id,
            'nilai_pengaturan' => 'sometimes|required',
            'deskripsi_pengaturan' => 'sometimes|required|string',
            'kategori_pengaturan' => 'sometimes|required|in:GENERAL,PAYMENT,SHIPPING,EMAIL,SECURITY,SEO',
            'is_aktif' => 'sometimes|required|boolean',
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
            'nama_pengaturan.required' => 'Nama pengaturan wajib diisi',
            'nama_pengaturan.string' => 'Nama pengaturan harus berupa teks',
            'nama_pengaturan.max' => 'Nama pengaturan maksimal 255 karakter',
            'nama_pengaturan.unique' => 'Nama pengaturan sudah digunakan',
            'nilai_pengaturan.required' => 'Nilai pengaturan wajib diisi',
            'deskripsi_pengaturan.required' => 'Deskripsi pengaturan wajib diisi',
            'deskripsi_pengaturan.string' => 'Deskripsi pengaturan harus berupa teks',
            'kategori_pengaturan.required' => 'Kategori pengaturan wajib diisi',
            'kategori_pengaturan.in' => 'Kategori pengaturan tidak valid',
            'is_aktif.required' => 'Status aktif wajib diisi',
            'is_aktif.boolean' => 'Status aktif harus berupa nilai boolean',
            'urutan_tampilan.integer' => 'Urutan tampilan harus berupa angka',
            'urutan_tampilan.min' => 'Urutan tampilan minimal 0',
        ];
    }
}