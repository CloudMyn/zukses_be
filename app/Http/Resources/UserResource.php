<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data pengguna
 */
class UserResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'nomor_telepon' => $this->nomor_telepon,
            'tipe_user' => $this->tipe_user,
            'status' => $this->status,
            'email_terverifikasi_pada' => $this->email_terverifikasi_pada,
            'telepon_terverifikasi_pada' => $this->telepon_terverifikasi_pada,
            'terakhir_login_pada' => $this->terakhir_login_pada,
            'url_foto_profil' => $this->url_foto_profil,
            'nama_depan' => $this->nama_depan,
            'nama_belakang' => $this->nama_belakang,
            'nama_lengkap' => $this->nama_lengkap,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tanggal_lahir' => $this->tanggal_lahir,
            'bio' => $this->bio,
            'url_media_sosial' => $this->url_media_sosial,
            'bidang_interests' => $this->bidang_interests,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}