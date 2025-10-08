<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data info pengiriman produk
 */
class ProductShippingInfoResource extends JsonResource
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
            'id_kota_asal' => $this->id_kota_asal,
            'nama_kota_asal' => $this->nama_kota_asal,
            'estimasi_pengiriman' => $this->estimasi_pengiriman,
            'berat_pengiriman' => $this->berat_pengiriman,
            'dimensi_pengiriman' => $this->dimensi_pengiriman,
            'biaya_pengemasan' => $this->biaya_pengemasan,
            'is_gratis_ongkir' => $this->is_gratis_ongkir,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}