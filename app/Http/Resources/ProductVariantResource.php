<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data varian produk
 */
class ProductVariantResource extends JsonResource
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
            'id_produk' => $this->id_produk,
            'id_admin' => $this->id_admin,
            'sku_varian' => $this->sku_varian,
            'nama_varian' => $this->nama_varian,
            'urutan_variant' => $this->urutan_variant,
            'harga' => $this->harga,
            'harga_diskon' => $this->harga_diskon,
            'diskon' => $this->diskon,
            'stok_varian' => $this->stok_varian,
            'berat_varian' => $this->berat_varian,
            'panjang_varian' => $this->panjang_varian,
            'lebar_varian' => $this->lebar_varian,
            'tinggi_varian' => $this->tinggi_varian,
            'gambar_varian' => $this->gambar_varian,
            'is_varian_aktif' => $this->is_varian_aktif,
            'is_varian_utama' => $this->is_varian_utama,
            'is_approved' => $this->is_approved,
            'atribut_varian' => $this->atribut_varian,
            'kode_barcode' => $this->kode_barcode,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}