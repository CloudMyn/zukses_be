<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellerReportUpdateRequest extends FormRequest
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
        $id = $this->route('sellerReport'); // Get the sellerReport ID from the route

        return [
            'id_penjual' => 'sometimes|required|exists:tb_penjual,id',
            'id_admin' => 'sometimes|required|exists:users,id',
            'jenis_laporan' => 'sometimes|required|in:PENJUALAN,PRODUK,PERFORMANCE,KEUANGAN',
            'periode_awal' => 'sometimes|required|date',
            'periode_akhir' => 'sometimes|required|date|after_or_equal:periode_awal',
            'data_laporan' => 'sometimes|required|array',
            'ringkasan' => 'sometimes|required|string',
            'status_laporan' => 'sometimes|required|in:DRAFT,TERKIRIM,SELESAI,DISETUJUI,DITOLAK',
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
            'id_penjual.required' => 'ID penjual wajib diisi',
            'id_penjual.exists' => 'Penjual yang dipilih tidak valid',
            'id_admin.required' => 'ID admin wajib diisi',
            'id_admin.exists' => 'Admin yang dipilih tidak valid',
            'jenis_laporan.required' => 'Jenis laporan wajib diisi',
            'jenis_laporan.in' => 'Jenis laporan tidak valid',
            'periode_awal.required' => 'Periode awal wajib diisi',
            'periode_awal.date' => 'Periode awal harus berupa format tanggal yang valid',
            'periode_akhir.required' => 'Periode akhir wajib diisi',
            'periode_akhir.date' => 'Periode akhir harus berupa format tanggal yang valid',
            'periode_akhir.after_or_equal' => 'Periode akhir harus sama atau setelah periode awal',
            'data_laporan.required' => 'Data laporan wajib diisi',
            'data_laporan.array' => 'Data laporan harus berupa array',
            'ringkasan.required' => 'Ringkasan wajib diisi',
            'ringkasan.string' => 'Ringkasan harus berupa teks',
            'status_laporan.required' => 'Status laporan wajib diisi',
            'status_laporan.in' => 'Status laporan tidak valid',
        ];
    }
}