<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data item pesanan
 */
class OrderItemResource extends JsonResource
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
            'id_seller' => $this->id_seller,
            'id_produk' => $this->id_produk,
            'id_varian' => $this->id_varian,
            'nama_produk' => $this->nama_produk,
            'gambar_produk' => $this->gambar_produk,
            'sku_produk' => $this->sku_produk,
            'atribut_varian' => $this->atribut_varian,
            'harga_satuan' => $this->harga_satuan,
            'jumlah_pesanan' => $this->jumlah_pesanan,
            'subtotal_harga' => $this->subtotal_harga,
            'diskon_item' => $this->diskon_item,
            'berat_item' => $this->berat_item,
            'status_item' => $this->status_item,
            'catatan_item' => $this->catatan_item,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}