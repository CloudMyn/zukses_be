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
            'nomor_verifikasi' => $this->nilai_verifikasi,
            'kode_verifikasi' => $this->kode_verifikasi,
            'waktu_kadaluarsa' => $this->kedaluwarsa_pada,
            'status_verifikasi' => $this->status_verifikasi,
            'created_at' => $this->dibuat_pada,
            'updated_at' => $this->diperbarui_pada,
        ];
    }
}