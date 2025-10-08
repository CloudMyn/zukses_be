<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data riwayat status pesanan
 */
class OrderStatusHistoryResource extends JsonResource
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
            'status_sebelumnya' => $this->status_sebelumnya,
            'status_sekarang' => $this->status_sekarang,
            'catatan_status' => $this->catatan_status,
            'id_pengubah' => $this->id_pengubah,
            'dibuat_pada' => $this->dibuat_pada,
            'created_at' => $this->created_at,
        ];
    }
}