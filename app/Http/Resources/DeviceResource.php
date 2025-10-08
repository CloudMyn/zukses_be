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
            'device_id' => $this->device_id,
            'device_type' => $this->device_type,
            'device_name' => $this->device_name,
            'operating_system' => $this->operating_system,
            'app_version' => $this->app_version,
            'push_token' => $this->push_token,
            'adalah_device_terpercaya' => $this->adalah_device_terpercaya,
            'terakhir_aktif_pada' => $this->terakhir_aktif_pada,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}