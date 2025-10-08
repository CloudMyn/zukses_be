<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data referensi order chat
 */
class ChatOrderReferenceResource extends JsonResource
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
            'pesan_id' => $this->pesan_id,
            'order_id' => $this->order_id,
            'marketplace_order_id' => $this->marketplace_order_id,
            'snapshot' => $this->snapshot,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}