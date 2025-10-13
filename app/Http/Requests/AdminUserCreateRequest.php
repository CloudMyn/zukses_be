<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserCreateRequest extends FormRequest
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
            'id_user' => 'required|exists:users,id',
            'nip' => 'required|string|unique:admin_users,nip',
            'nama_lengkap' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'departemen' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'tanggal_mulai_bekerja' => 'required|date',
            'is_active' => 'required|boolean',
            'catatan' => 'nullable|string',
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
            'id_user.required' => 'ID user wajib diisi',
            'id_user.exists' => 'User yang dipilih tidak valid',
            'nip.required' => 'NIP wajib diisi',
            'nip.string' => 'NIP harus berupa teks',
            'nip.unique' => 'NIP sudah digunakan',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'nama_lengkap.string' => 'Nama lengkap harus berupa teks',
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi',
            'nomor_telepon.string' => 'Nomor telepon harus berupa teks',
            'nomor_telepon.max' => 'Nomor telepon maksimal 20 karakter',
            'departemen.required' => 'Departemen wajib diisi',
            'departemen.string' => 'Departemen harus berupa teks',
            'departemen.max' => 'Departemen maksimal 100 karakter',
            'jabatan.required' => 'Jabatan wajib diisi',
            'jabatan.string' => 'Jabatan harus berupa teks',
            'jabatan.max' => 'Jabatan maksimal 100 karakter',
            'tanggal_mulai_bekerja.required' => 'Tanggal mulai bekerja wajib diisi',
            'tanggal_mulai_bekerja.date' => 'Tanggal mulai bekerja harus berupa format tanggal yang valid',
            'is_active.required' => 'Status aktif wajib diisi',
            'is_active.boolean' => 'Status aktif harus berupa nilai boolean',
        ];
    }
}