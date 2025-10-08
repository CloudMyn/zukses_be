<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data peserta percakapan chat
 */
class ChatParticipantResource extends JsonResource
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
            'user_id' => $this->user_id,
            'shop_profile_id' => $this->shop_profile_id,
            'role' => $this->role,
            'bergabung_pada' => $this->bergabung_pada,
            'keluar_pada' => $this->keluar_pada,
            'last_read_message_id' => $this->last_read_message_id,
            'terakhir_dibaca_pada' => $this->terakhir_dibaca_pada,
            'jumlah_belum_dibaca' => $this->jumlah_belum_dibaca,
            'dihentikan_hingga' => $this->dihentikan_hingga,
            'is_blocked' => $this->is_blocked,
            'preferences' => $this->preferences,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}