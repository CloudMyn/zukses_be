<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data pengiriman pesanan
 */
class OrderShippingResource extends JsonResource
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
            'id_pesanan' => $this->id_pesanan,
            'id_kurir' => $this->id_kurir,
            'nama_kurir' => $this->nama_kurir,
            'tipe_layanan' => $this->tipe_layanan,
            'no_resi' => $this->no_resi,
            'status_pengiriman' => $this->status_pengiriman,
            'estimasi_pengiriman' => $this->estimasi_pengiriman,
            'biaya_pengiriman' => $this->biaya_pengiriman,
            'biaya_asuransi' => $this->biaya_asuransi,
            'biaya_lainnya' => $this->biaya_lainnya,
            'alamat_pengiriman' => $this->alamat_pengiriman,
            'catatan_pengiriman' => $this->catatan_pengiriman,
            'link_tracking' => $this->link_tracking,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}