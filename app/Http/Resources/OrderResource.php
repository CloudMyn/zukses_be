<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data pesanan
 */
class OrderResource extends JsonResource
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
            'nomor_pesanan' => $this->nomor_pesanan,
            'id_customer' => $this->id_customer,
            'id_alamat_pengiriman' => $this->id_alamat_pengiriman,
            'status_pesanan' => $this->status_pesanan,
            'status_pembayaran' => $this->status_pembayaran,
            'total_items' => $this->total_items,
            'total_berat' => $this->total_berat,
            'subtotal_produk' => $this->subtotal_produk,
            'total_diskon_produk' => $this->total_diskon_produk,
            'total_ongkir' => $this->total_ongkir,
            'total_biaya_layanan' => $this->total_biaya_layanan,
            'total_pajak' => $this->total_pajak,
            'total_pembayaran' => $this->total_pembayaran,
            'metode_pembayaran' => $this->metode_pembayaran,
            'bank_pembayaran' => $this->bank_pembayaran,
            'va_number' => $this->va_number,
            'deadline_pembayaran' => $this->deadline_pembayaran,
            'tanggal_dibayar' => $this->tanggal_dibayar,
            'no_resi' => $this->no_resi,
            'catatan_pesanan' => $this->catatan_pesanan,
            'tanggal_pengiriman' => $this->tanggal_pengiriman,
            'tanggal_selesai' => $this->tanggal_selesai,
            'tanggal_dibatalkan' => $this->tanggal_dibatalkan,
            'alasan_pembatalan' => $this->alasan_pembatalan,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}