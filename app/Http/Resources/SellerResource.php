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
            'slug_toko' => $this->slug_toko,
            'deskripsi_toko' => $this->deskripsi_toko,
            'logo_toko' => $this->logo_toko,
            'banner_toko' => $this->banner_toko,
            'nomor_ktp' => $this->nomor_ktp,
            'foto_ktp' => $this->foto_ktp,
            'nomor_npwp' => $this->nomor_npwp,
            'foto_npwp' => $this->foto_npwp,
            'jenis_usaha' => $this->jenis_usaha,
            'status_verifikasi' => $this->status_verifikasi,
            'tanggal_verifikasi' => $this->tanggal_verifikasi,
            'id_verifikator' => $this->id_verifikator,
            'catatan_verifikasi' => $this->catatan_verifikasi,
            'rating_toko' => $this->rating_toko,
            'total_penjualan' => $this->total_penjualan,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
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