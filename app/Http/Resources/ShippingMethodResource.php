<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data metode pengiriman
 */
class ShippingMethodResource extends JsonResource
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
            'nama_kurir' => $this->nama_kurir,
            'tipe_layanan' => $this->tipe_layanan,
            'deskripsi_layanan' => $this->deskripsi_layanan,
            'logo_kurir' => $this->logo_kurir,
            'is_aktif' => $this->is_aktif,
            'is_cargo' => $this->is_cargo,
            'estimasi_pengiriman_min' => $this->estimasi_pengiriman_min,
            'estimasi_pengiriman_max' => $this->estimasi_pengiriman_max,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}