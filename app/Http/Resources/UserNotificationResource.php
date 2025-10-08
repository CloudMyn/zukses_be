<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data notifikasi pengguna
 */
class UserNotificationResource extends JsonResource
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
            'tipe_notifikasi' => $this->tipe_notifikasi,
            'judul_notifikasi' => $this->judul_notifikasi,
            'isi_notifikasi' => $this->isi_notifikasi,
            'data_notifikasi' => $this->data_notifikasi,
            'url_redirect' => $this->url_redirect,
            'gambar_notifikasi' => $this->gambar_notifikasi,
            'is_dibaca' => $this->is_dibaca,
            'is_dikirim_push' => $this->is_dikirim_push,
            'is_dikirim_email' => $this->is_dikirim_email,
            'is_dikirim_sms' => $this->is_dikirim_sms,
            'tanggal_dibaca' => $this->tanggal_dibaca,
            'kadaluarsa_pada' => $this->kadaluarsa_pada,
            'dibuat_pada' => $this->dibuat_pada,
            'created_at' => $this->created_at,
        ];
    }
}