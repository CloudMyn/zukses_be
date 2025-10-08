<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data produk
 */
class ProductResource extends JsonResource
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
            'id_seller' => $this->id_seller,
            'id_admin' => $this->id_admin,
            'sku' => $this->sku,
            'nama_produk' => $this->nama_produk,
            'slug_produk' => $this->slug_produk,
            'deskripsi_lengkap' => $this->deskripsi_lengkap,
            'kondisi_produk' => $this->kondisi_produk,
            'status_produk' => $this->status_produk,
            'berat_paket' => $this->berat_paket,
            'panjang_paket' => $this->panjang_paket,
            'lebar_paket' => $this->lebar_paket,
            'tinggi_paket' => $this->tinggi_paket,
            'harga_minimum' => $this->harga_minimum,
            'harga_maximum' => $this->harga_maximum,
            'jumlah_stok' => $this->jumlah_stok,
            'stok_minimum' => $this->stok_minimum,
            'jumlah_terjual' => $this->jumlah_terjual,
            'jumlah_dilihat' => $this->jumlah_dilihat,
            'jumlah_difavoritkan' => $this->jumlah_difavoritkan,
            'rating_produk' => $this->rating_produk,
            'jumlah_ulasan' => $this->jumlah_ulasan,
            'is_produk_unggulan' => $this->is_produk_unggulan,
            'is_produk_preorder' => $this->is_produk_preorder,
            'is_cod' => $this->is_cod,
            'is_approved' => $this->is_approved,
            'is_product_varian' => $this->is_product_varian,
            'waktu_preorder' => $this->waktu_preorder,
            'garansi_produk' => $this->garansi_produk,
            'etalase_kategori' => $this->etalase_kategori,
            'tag_produk' => $this->tag_produk,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'video_produk' => $this->video_produk,
            'tanggal_dipublikasikan' => $this->tanggal_dipublikasikan,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}