<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data pesan chat
 */
class ChatMessageResource extends JsonResource
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
            'percakapan_id' => $this->percakapan_id,
            'pengirim_user_id' => $this->pengirim_user_id,
            'pengirim_shop_profile_id' => $this->pengirim_shop_profile_id,
            'konten' => $this->konten,
            'tipe_konten' => $this->tipe_konten,
            'metadata' => $this->metadata,
            'parent_message_id' => $this->parent_message_id,
            'reply_to_message_id' => $this->reply_to_message_id,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'diedit_pada' => $this->diedit_pada,
            'is_dihapus' => $this->is_dihapus,
            'dihapus_pada' => $this->dihapus_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}