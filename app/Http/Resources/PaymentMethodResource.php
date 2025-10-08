<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data metode pembayaran
 */
class PaymentMethodResource extends JsonResource
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
            'nama_pembayaran' => $this->nama_pembayaran,
            'tipe_pembayaran' => $this->tipe_pembayaran,
            'provider_pembayaran' => $this->provider_pembayaran,
            'logo_pembayaran' => $this->logo_pembayaran,
            'deskripsi_pembayaran' => $this->deskripsi_pembayaran,
            'biaya_admin_percent' => $this->biaya_admin_percent,
            'biaya_admin_fixed' => $this->biaya_admin_fixed,
            'minimum_pembayaran' => $this->minimum_pembayaran,
            'maksimum_pembayaran' => $this->maksimum_pembayaran,
            'is_aktif' => $this->is_aktif,
            'urutan_tampilan' => $this->urutan_tampilan,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}