<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data verifikasi pengguna
 */
class VerificationResource extends JsonResource
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
            'jenis_verifikasi' => $this->jenis_verifikasi,
            'nilai_verifikasi' => $this->nilai_verifikasi,
            'kode_verifikasi' => $this->kode_verifikasi,
            'kedaluwarsa_pada' => $this->kedaluwarsa_pada,
            'telah_digunakan' => $this->telah_digunakan,
            'jumlah_coba' => $this->jumlah_coba,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}