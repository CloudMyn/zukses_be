<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data reaksi pesan chat
 */
class MessageReactionResource extends JsonResource
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
            'user_id' => $this->user_id,
            'reaksi' => $this->reaksi,
            'direaksi_pada' => $this->direaksi_pada,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}