<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data keranjang belanja
 */
class CartResource extends JsonResource
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
            'id_user' => $this->id_user,
            'session_id' => $this->session_id,
            'id_seller' => $this->id_seller,
            'total_items' => $this->total_items,
            'total_berat' => $this->total_berat,
            'total_harga' => $this->total_harga,
            'total_diskon' => $this->total_diskon,
            'is_cart_aktif' => $this->is_cart_aktif,
            'kadaluarsa_pada' => $this->kadaluarsa_pada,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}