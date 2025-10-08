<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data kategori produk
 */
class CategoryProductResource extends JsonResource
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
            'nama_kategori' => $this->nama_kategori,
            'slug_kategori' => $this->slug_kategori,
            'deskripsi_kategori' => $this->deskripsi_kategori,
            'gambar_kategori' => $this->gambar_kategori,
            'icon_kategori' => $this->icon_kategori,
            'id_kategori_induk' => $this->id_kategori_induk,
            'level_kategori' => $this->level_kategori,
            'urutan_tampilan' => $this->urutan_tampilan,
            'is_kategori_aktif' => $this->is_kategori_aktif,
            'is_kategori_featured' => $this->is_kategori_featured,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}