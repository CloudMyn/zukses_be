<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data penjual
 */
class SellerResource extends JsonResource
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
            'nama_toko' => $this->nama_toko,
            'deskripsi' => $this->deskripsi_toko, // API-friendly alias
            'foto_profil' => $this->logo_toko, // API-friendly alias
            'created_at' => $this->dibuat_pada,
            'updated_at' => $this->diperbarui_pada,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'username' => $this->user->username,
                    'email' => $this->user->email,
                    'tipe_user' => $this->user->tipe_user,
                    'no_hp' => $this->user->nomor_telepon, // API-friendly alias
                    'status' => $this->user->status
                ];
            })
        ];
    }
}