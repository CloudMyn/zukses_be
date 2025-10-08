<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data gambar produk
 */
class ProductImageResource extends JsonResource
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
            'url_gambar' => $this->url_gambar,
            'alt_text' => $this->alt_text,
            'urutan_gambar' => $this->urutan_gambar,
            'is_gambar_utama' => $this->is_gambar_utama,
            'tipe_gambar' => $this->tipe_gambar,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}