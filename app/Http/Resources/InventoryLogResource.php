<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data log inventori
 */
class InventoryLogResource extends JsonResource
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
            'id_produk' => $this->id_produk,
            'id_varian' => $this->id_varian,
            'tipe_transaksi' => $this->tipe_transaksi,
            'jumlah_transaksi' => $this->jumlah_transaksi,
            'stok_sebelum' => $this->stok_sebelum,
            'stok_sesudah' => $this->stok_sesudah,
            'alasan_transaksi' => $this->alasan_transaksi,
            'id_operator' => $this->id_operator,
            'catatan_tambahan' => $this->catatan_tambahan,
            'dibuat_pada' => $this->dibuat_pada,
            'created_at' => $this->created_at,
        ];
    }
}