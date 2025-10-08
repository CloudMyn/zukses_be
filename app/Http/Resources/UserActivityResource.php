<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data aktivitas pengguna
 */
class UserActivityResource extends JsonResource
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
            'sesi_id' => $this->sesi_id,
            'tipe_aktivitas' => $this->tipe_aktivitas,
            'data_aktivitas' => $this->data_aktivitas,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'referrer' => $this->referrer,
            'halaman_asal' => $this->halaman_asal,
            'dibuat_pada' => $this->dibuat_pada,
            'created_at' => $this->created_at,
        ];
    }
}