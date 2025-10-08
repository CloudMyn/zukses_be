<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data tarif pengiriman
 */
class ShippingRateResource extends JsonResource
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
            'id_kurir' => $this->id_kurir,
            'id_kota_asal' => $this->id_kota_asal,
            'id_kota_tujuan' => $this->id_kota_tujuan,
            'berat_min' => $this->berat_min,
            'berat_max' => $this->berat_max,
            'harga_ongkir' => $this->harga_ongkir,
            'estimasi_pengiriman' => $this->estimasi_pengiriman,
            'is_aktif' => $this->is_aktif,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}