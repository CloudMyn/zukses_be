<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data ulasan produk
 */
class ProductReviewResource extends JsonResource
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
            'id_varian' => $this->id_varian,
            'id_pembeli' => $this->id_pembeli,
            'id_pesanan' => $this->id_pesanan,
            'rating_produk' => $this->rating_produk,
            'rating_akurasi_produk' => $this->rating_akurasi_produk,
            'rating_kualitas_produk' => $this->rating_kualitas_produk,
            'rating_pengiriman_produk' => $this->rating_pengiriman_produk,
            'komentar_ulasan' => $this->komentar_ulasan,
            'is_ulasan_anonim' => $this->is_ulasan_anonim,
            'is_ulasan_terverifikasi' => $this->is_ulasan_terverifikasi,
            'is_ditampilkan' => $this->is_ditampilkan,
            'jumlah_suka' => $this->jumlah_suka,
            'id_review_parent' => $this->id_review_parent,
            'tanggal_ulasan' => $this->tanggal_ulasan,
            'dibuat_pada' => $this->dibuat_pada,
            'diperbarui_pada' => $this->diperbarui_pada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}