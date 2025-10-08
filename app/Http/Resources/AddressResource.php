<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data alamat
 */
class AddressResource extends JsonResource
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
            'label_alamat' => $this->label_alamat,
            'nama_penerima' => $this->nama_penerima,
            'nomor_telepon_penerima' => $this->nomor_telepon_penerima,
            'alamat_lengkap' => $this->alamat_lengkap,
            'id_provinsi' => $this->id_provinsi,
            'nama_provinsi' => $this->nama_provinsi,
            'id_kabupaten' => $this->id_kabupaten,
            'nama_kabupaten' => $this->nama_kabupaten,
            'id_kecamatan' => $this->id_kecamatan,
            'nama_kecamatan' => $this->nama_kecamatan,
            'id_kelurahan' => $this->id_kelurahan,
            'nama_kelurahan' => $this->nama_kelurahan,
            'kode_pos' => $this->kode_pos,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'adalah_alamat_utama' => $this->adalah_alamat_utama,
            'tipe_alamat' => $this->tipe_alamat,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}