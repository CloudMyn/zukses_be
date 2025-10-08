<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data pengaturan sistem
 */
class SystemSettingResource extends JsonResource
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
            'kunci_pengaturan' => $this->kunci_pengaturan,
            'nilai_pengaturan' => $this->nilai_pengaturan,
            'tipe_pengaturan' => $this->tipe_pengaturan,
            'grup_pengaturan' => $this->grup_pengaturan,
            'deskripsi_pengaturan' => $this->deskripsi_pengaturan,
            'is_public' => $this->is_public,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}