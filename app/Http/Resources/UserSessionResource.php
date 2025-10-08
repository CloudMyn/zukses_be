<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data sesi pengguna
 */
class UserSessionResource extends JsonResource
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
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'payload' => $this->payload,
            'aktivitas_terakhir' => $this->aktivitas_terakhir,
            'dibuat_pada' => $this->dibuat_pada,
            'created_at' => $this->created_at,
        ];
    }
}