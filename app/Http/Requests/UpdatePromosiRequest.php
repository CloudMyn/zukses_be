<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromosiRequest extends FormRequest
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
        $promosiId = $this->route('promosi');
        
        return [
            'kode_promosi' => 'sometimes|required|string|unique:tb_promosi,kode_promosi,' . $promosiId . '|max:50',
            'nama_promosi' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis_promosi' => 'sometimes|in:KODE_PROMOSI,OTOMATIS,MEMBER,KELOMPOK_PRODUK',
            'tipe_diskon' => 'sometimes|in:PERSEN,NOMINAL,BONUS_PRODUK',
            'nilai_diskon' => 'sometimes|required|numeric|min:0',
            'jumlah_maksimum_penggunaan' => 'sometimes|required|integer|min:0',
            'jumlah_penggunaan_saat_ini' => 'sometimes|required|integer|min:0',
            'jumlah_maksimum_penggunaan_per_pengguna' => 'sometimes|required|integer|min:0',
            'tanggal_mulai' => 'sometimes|required|date',
            'tanggal_berakhir' => 'sometimes|required|date|after_or_equal:tanggal_mulai',
            'minimum_pembelian' => 'sometimes|required|numeric|min:0',
            'id_kategori_produk' => 'nullable|exists:tb_kategori_produk,id',
            'dapat_digabungkan' => 'sometimes|required|boolean',
            'status_aktif' => 'sometimes|required|boolean',
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
            'kode_promosi.unique' => 'Kode promosi sudah digunakan.',
            'nama_promosi.required' => 'Nama promosi wajib diisi.',
            'jenis_promosi.in' => 'Jenis promosi tidak valid.',
            'tipe_diskon.in' => 'Tipe diskon tidak valid.',
            'nilai_diskon.required' => 'Nilai diskon wajib diisi.',
            'jumlah_maksimum_penggunaan.required' => 'Jumlah maksimum penggunaan wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_berakhir.required' => 'Tanggal berakhir wajib diisi.',
            'minimum_pembelian.required' => 'Minimum pembelian wajib diisi.',
        ];
    }
}