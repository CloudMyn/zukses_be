<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePromosiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tipe_user === 'ADMIN';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_promosi' => 'required|string|unique:tb_promosi,kode_promosi|max:50',
            'nama_promosi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis_promosi' => 'required|in:KODE_PROMOSI,OTOMATIS,MEMBER,KELOMPOK_PRODUK',
            'tipe_diskon' => 'required|in:PERSEN,NOMINAL,BONUS_PRODUK',
            'nilai_diskon' => 'required|numeric|min:0',
            'jumlah_maksimum_penggunaan' => 'required|integer|min:0',
            'jumlah_maksimum_penggunaan_per_pengguna' => 'required|integer|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'minimum_pembelian' => 'required|numeric|min:0',
            'id_kategori_produk' => 'nullable|exists:tb_kategori_produk,id',
            'dapat_digabungkan' => 'required|boolean',
            'status_aktif' => 'required|boolean',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'kode_promosi.required' => 'Kode promosi wajib diisi.',
            'kode_promosi.unique' => 'Kode promosi sudah digunakan.',
            'nama_promosi.required' => 'Nama promosi wajib diisi.',
            'jenis_promosi.required' => 'Jenis promosi wajib dipilih.',
            'tipe_diskon.required' => 'Tipe diskon wajib dipilih.',
            'nilai_diskon.required' => 'Nilai diskon wajib diisi.',
            'jumlah_maksimum_penggunaan.required' => 'Jumlah maksimum penggunaan wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_berakhir.required' => 'Tanggal berakhir wajib diisi.',
            'minimum_pembelian.required' => 'Minimum pembelian wajib diisi.',
        ];
    }
}