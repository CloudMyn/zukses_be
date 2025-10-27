<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data perangkat
 */
class DeviceResource extends JsonResource
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
            'device_id' => $this->device_id,
            'device_type' => $this->device_type,
            'device_name' => $this->device_name,
            'operating_system' => $this->operating_system,
            'app_version' => $this->app_version,
            'is_trusted' => $this->is_trusted,
            'last_used_at' => $this->terakhir_aktif_pada,
            'created_at' => $this->dibuat_pada,
            'updated_at' => $this->diperbarui_pada,
        ];
    }
}