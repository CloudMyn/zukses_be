<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data item keranjang
 */
class CartItemResource extends JsonResource
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
            'id_cart' => $this->id_cart,
            'id_produk' => $this->id_produk,
            'id_varian' => $this->id_varian,
            'nama_produk' => $this->nama_produk,
            'gambar_produk' => $this->gambar_produk,
            'harga_satuan' => $this->harga_satuan,
            'jumlah_pesanan' => $this->jumlah_pesanan,
            'subtotal_harga' => $this->subtotal_harga,
            'berat_item' => $this->berat_item,
            'catatan_pesanan' => $this->catatan_pesanan,
            'is_item_aktif' => $this->is_item_aktif,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}