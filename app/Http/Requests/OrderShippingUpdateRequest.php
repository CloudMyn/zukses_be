<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderShippingUpdateRequest extends FormRequest
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
        $id = $this->route('orderShipping'); // Get the orderShipping ID from the route

        return [
            'id_order' => 'sometimes|required|exists:tb_order,id',
            'id_metode_pengiriman' => 'sometimes|required|exists:metode_pengiriman,id',
            'pengirim' => 'sometimes|required|string|max:255',
            'penerima' => 'sometimes|required|string|max:255',
            'alamat_pengiriman' => 'sometimes|required|string',
            'kota_pengiriman' => 'sometimes|required|string|max:255',
            'provinsi_pengiriman' => 'sometimes|required|string|max:255',
            'kode_pos_pengiriman' => 'sometimes|required|string|max:20',
            'negara_pengiriman' => 'sometimes|required|string|max:255',
            'kota_tujuan' => 'sometimes|required|string|max:255',
            'provinsi_tujuan' => 'sometimes|required|string|max:255',
            'kode_pos_tujuan' => 'sometimes|required|string|max:20',
            'negara_tujuan' => 'sometimes|required|string|max:255',
            'berat_total' => 'sometimes|required|numeric|min:0',
            'jumlah_barang' => 'sometimes|required|integer|min:0',
            'biaya_pengiriman' => 'sometimes|required|numeric|min:0',
            'perkiraan_sampai' => 'nullable|date',
            'nomor_resi' => 'nullable|string|max:255',
            'status_pengiriman' => 'sometimes|required|in:DIPROSES,DIKIRIM,DITERIMA,DIBATALKAN',
            'keterangan' => 'nullable|string',
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
            'id_order.required' => 'ID order wajib diisi',
            'id_order.exists' => 'Order yang dipilih tidak valid',
            'id_metode_pengiriman.required' => 'Metode pengiriman wajib diisi',
            'id_metode_pengiriman.exists' => 'Metode pengiriman yang dipilih tidak valid',
            'pengirim.required' => 'Nama pengirim wajib diisi',
            'penerima.required' => 'Nama penerima wajib diisi',
            'alamat_pengiriman.required' => 'Alamat pengiriman wajib diisi',
            'kota_pengiriman.required' => 'Kota pengiriman wajib diisi',
            'provinsi_pengiriman.required' => 'Provinsi pengiriman wajib diisi',
            'kode_pos_pengiriman.required' => 'Kode pos pengiriman wajib diisi',
            'negara_pengiriman.required' => 'Negara pengiriman wajib diisi',
            'kota_tujuan.required' => 'Kota tujuan wajib diisi',
            'provinsi_tujuan.required' => 'Provinsi tujuan wajib diisi',
            'kode_pos_tujuan.required' => 'Kode pos tujuan wajib diisi',
            'negara_tujuan.required' => 'Negara tujuan wajib diisi',
            'berat_total.required' => 'Berat total wajib diisi',
            'berat_total.numeric' => 'Berat total harus berupa angka',
            'jumlah_barang.required' => 'Jumlah barang wajib diisi',
            'jumlah_barang.integer' => 'Jumlah barang harus berupa angka bulat',
            'biaya_pengiriman.required' => 'Biaya pengiriman wajib diisi',
            'biaya_pengiriman.numeric' => 'Biaya pengiriman harus berupa angka',
            'status_pengiriman.required' => 'Status pengiriman wajib diisi',
            'status_pengiriman.in' => 'Status pengiriman tidak valid',
        ];
    }
}