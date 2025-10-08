<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data transaksi pembayaran
 */
class PaymentTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'id_pesanan' => $this->id_pesanan,
            'id_metode_pembayaran' => $this->id_metode_pembayaran,
            'reference_id' => $this->reference_id,
            'jumlah_pembayaran' => $this->jumlah_pembayaran,
            'status_transaksi' => $this->status_transaksi,
            'channel_pembayaran' => $this->channel_pembayaran,
            'va_number' => $this->va_number,
            'qr_code' => $this->qr_code,
            'deep_link' => $this->deep_link,
            'tanggal_kadaluarsa' => $this->tanggal_kadaluarsa,
            'tanggal_bayar' => $this->tanggal_bayar,
            'response_gateway' => $this->response_gateway,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}