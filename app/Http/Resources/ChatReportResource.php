<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data laporan percakapan chat
 */
class ChatReportResource extends JsonResource
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
            'reporter_id' => $this->reporter_id,
            'alasan' => $this->alasan,
            'metadata' => $this->metadata,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}