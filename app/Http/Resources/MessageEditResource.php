<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data edit pesan chat
 */
class MessageEditResource extends JsonResource
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
            'editor_id' => $this->editor_id,
            'konten_sebelumnya' => $this->konten_sebelumnya,
            'alasan_edit' => $this->alasan_edit,
            'diedit_pada' => $this->diedit_pada,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}