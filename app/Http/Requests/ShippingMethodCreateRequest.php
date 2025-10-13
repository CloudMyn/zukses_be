<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShippingMethodCreateRequest extends FormRequest
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
            'nama_metode' => 'required|string|unique:metode_pengiriman,nama_metode|max:255',
            'kode_metode' => 'required|string|unique:metode_pengiriman,kode_metode|max:50',
            'deskripsi_metode' => 'nullable|string|max:1000',
            'gambar_metode' => 'nullable|string|max:2048',
            'is_aktif' => 'boolean',
            'urutan_tampilan' => 'required|integer|min:0',
            'tipe_pengiriman' => 'required|in:STANDARD,EXPRESS,SAME_DAY,NEXT_DAY',
            'min_berat' => 'required|numeric|min:0',
            'max_berat' => 'required|numeric|min:0|gte:min_berat',
            'min_nilai' => 'required|numeric|min:0',
            'max_nilai' => 'required|numeric|min:0|gte:min_nilai',
            'biaya_minimum' => 'required|numeric|min:0',
            'biaya_maximum' => 'required|numeric|min:0|gte:biaya_minimum',
            'konfigurasi_metode' => 'nullable|json',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'nama_metode.required' => 'Nama metode pengiriman wajib diisi.',
            'nama_metode.unique' => 'Nama metode pengiriman sudah digunakan.',
            'nama_metode.max' => 'Nama metode pengiriman maksimal 255 karakter.',
            'kode_metode.required' => 'Kode metode pengiriman wajib diisi.',
            'kode_metode.unique' => 'Kode metode pengiriman sudah digunakan.',
            'kode_metode.max' => 'Kode metode pengiriman maksimal 50 karakter.',
            'deskripsi_metode.max' => 'Deskripsi metode pengiriman maksimal 1000 karakter.',
            'gambar_metode.max' => 'URL gambar metode pengiriman maksimal 2048 karakter.',
            'urutan_tampilan.required' => 'Urutan tampilan wajib diisi.',
            'urutan_tampilan.integer' => 'Urutan tampilan harus berupa angka.',
            'urutan_tampilan.min' => 'Urutan tampilan tidak boleh negatif.',
            'tipe_pengiriman.required' => 'Tipe pengiriman wajib diisi.',
            'tipe_pengiriman.in' => 'Tipe pengiriman harus salah satu dari: STANDARD, EXPRESS, SAME_DAY, NEXT_DAY.',
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
            'biaya_minimum.required' => 'Biaya minimum wajib diisi.',
            'biaya_minimum.numeric' => 'Biaya minimum harus berupa angka.',
            'biaya_minimum.min' => 'Biaya minimum tidak boleh negatif.',
            'biaya_maximum.required' => 'Biaya maksimum wajib diisi.',
            'biaya_maximum.numeric' => 'Biaya maksimum harus berupa angka.',
            'biaya_maximum.min' => 'Biaya maksimum tidak boleh negatif.',
            'biaya_maximum.gte' => 'Biaya maksimum harus lebih besar atau sama dengan biaya minimum.',
            'konfigurasi_metode.json' => 'Konfigurasi metode harus dalam format JSON.',
        ];
    }
}