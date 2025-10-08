<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $address = $this->route('address');
        return $this->user()->tipe_user === 'ADMIN' || $this->user()->id === $address->id_user;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'label_alamat' => 'required|string|max:255',
            'nama_penerima' => 'required|string|max:255',
            'nomor_telepon_penerima' => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
            'id_provinsi' => 'required|integer',
            'nama_provinsi' => 'required|string|max:255',
            'id_kabupaten' => 'required|integer',
            'nama_kabupaten' => 'required|string|max:255',
            'id_kecamatan' => 'required|integer',
            'nama_kecamatan' => 'required|string|max:255',
            'id_kelurahan' => 'required|integer',
            'nama_kelurahan' => 'required|string|max:255',
            'kode_pos' => 'required|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'adalah_alamat_utama' => 'boolean',
            'tipe_alamat' => 'required|in:RUMAH,KANTOR,GUDANG,LAINNYA',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'label_alamat.required' => 'Label alamat wajib diisi.',
            'nama_penerima.required' => 'Nama penerima wajib diisi.',
            'nomor_telepon_penerima.required' => 'Nomor telepon penerima wajib diisi.',
            'alamat_lengkap.required' => 'Alamat lengkap wajib diisi.',
            'id_provinsi.required' => 'ID provinsi wajib diisi.',
            'nama_provinsi.required' => 'Nama provinsi wajib diisi.',
            'id_kabupaten.required' => 'ID kabupaten wajib diisi.',
            'nama_kabupaten.required' => 'Nama kabupaten wajib diisi.',
            'id_kecamatan.required' => 'ID kecamatan wajib diisi.',
            'nama_kecamatan.required' => 'Nama kecamatan wajib diisi.',
            'id_kelurahan.required' => 'ID kelurahan wajib diisi.',
            'nama_kelurahan.required' => 'Nama kelurahan wajib diisi.',
            'kode_pos.required' => 'Kode pos wajib diisi.',
        ];
    }
}