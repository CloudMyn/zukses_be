<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data percakapan chat
 */
class ChatConversationResource extends JsonResource
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
            'tipe' => $this->tipe,
            'judul' => $this->judul,
            'owner_user_id' => $this->owner_user_id,
            'owner_shop_profile_id' => $this->owner_shop_profile_id,
            'metadata' => $this->metadata,
            'last_message_id' => $this->last_message_id,
            'last_message_at' => $this->last_message_at,
            'is_open' => $this->is_open,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}