<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShippingRateCreateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'id_metode_pengiriman' => [
                'required',
                'exists:metode_pengiriman,id',
                Rule::unique('tarif_pengiriman', 'id_metode_pengiriman')
                    ->where('id_asal', $this->id_asal)
                    ->where('id_tujuan', $this->id_tujuan)
            ],
            'id_asal' => 'required|integer',
            'id_tujuan' => 'required|integer',
            'tipe_jarak' => 'required|in:LOKAL,ANTAR_KOTA,ANTAR_PROVINSI,INTERNASIONAL',
            'min_berat' => 'required|numeric|min:0',
            'max_berat' => 'required|numeric|min:0|gte:min_berat',
            'min_nilai' => 'required|numeric|min:0',
            'max_nilai' => 'required|numeric|min:0|gte:min_nilai',
            'biaya_dasar' => 'required|numeric|min:0',
            'biaya_per_kg' => 'required|numeric|min:0',
            'estimasi_hari' => 'required|integer|min:1',
            'is_aktif' => 'boolean',
            'prioritas' => 'required|integer|min:0',
            'keterangan_tarif' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'id_metode_pengiriman.required' => 'ID metode pengiriman wajib diisi.',
            'id_metode_pengiriman.exists' => 'ID metode pengiriman tidak valid.',
            'id_metode_pengiriman.unique' => 'Tarif pengiriman untuk rute ini sudah ada.',
            'id_asal.required' => 'ID asal wajib diisi.',
            'id_asal.integer' => 'ID asal harus berupa angka.',
            'id_tujuan.required' => 'ID tujuan wajib diisi.',
            'id_tujuan.integer' => 'ID tujuan harus berupa angka.',
            'tipe_jarak.required' => 'Tipe jarak wajib diisi.',
            'tipe_jarak.in' => 'Tipe jarak harus salah satu dari: LOKAL, ANTAR_KOTA, ANTAR_PROVINSI, INTERNASIONAL.',
            'min_berat.required' => 'Berat minimum wajib diisi.',
            'min_berat.numeric' => 'Berat minimum harus berupa angka.',
            'min_berat.min' => 'Berat minimum tidak boleh negatif.',
            'max_berat.required' => 'Berat maksimum wajib diisi.',
            'max_berat.numeric' => 'Berat maksimum harus berupa angka.',
            'max_berat.min' => 'Berat maksimum tidak boleh negatif.',
            'max_berat.gte' => 'Berat maksimum harus lebih besar atau sama dengan berat minimum.',
            'min_nilai.required' => 'Nilai minimum wajib diisi.',
            'min_nilai.numeric' => 'Nilai minimum harus berupa angka.',
            'min_nilai.min' => 'Nilai minimum tidak boleh negatif.',
            'max_nilai.required' => 'Nilai maksimum wajib diisi.',
            'max_nilai.numeric' => 'Nilai maksimum harus berupa angka.',
            'max_nilai.min' => 'Nilai maksimum tidak boleh negatif.',
            'max_nilai.gte' => 'Nilai maksimum harus lebih besar atau sama dengan nilai minimum.',
            'biaya_dasar.required' => 'Biaya dasar wajib diisi.',
            'biaya_dasar.numeric' => 'Biaya dasar harus berupa angka.',
            'biaya_dasar.min' => 'Biaya dasar tidak boleh negatif.',
            'biaya_per_kg.required' => 'Biaya per kg wajib diisi.',
            'biaya_per_kg.numeric' => 'Biaya per kg harus berupa angka.',
            'biaya_per_kg.min' => 'Biaya per kg tidak boleh negatif.',
            'estimasi_hari.required' => 'Estimasi hari wajib diisi.',
            'estimasi_hari.integer' => 'Estimasi hari harus berupa angka.',
            'estimasi_hari.min' => 'Estimasi hari minimal 1.',
            'prioritas.required' => 'Prioritas wajib diisi.',
            'prioritas.integer' => 'Prioritas harus berupa angka.',
            'prioritas.min' => 'Prioritas tidak boleh negatif.',
            'keterangan_tarif.max' => 'Keterangan tarif maksimal 500 karakter.',
        ];
    }
}