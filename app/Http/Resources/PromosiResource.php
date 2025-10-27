<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CategoryProductResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProductCollection;

class PromosiResource extends JsonResource
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
            'kode_promosi' => $this->kode_promosi,
            'nama_promosi' => $this->nama_promosi,
            'deskripsi' => $this->deskripsi,
            'jenis_promosi' => $this->jenis_promosi,
            'tipe_diskon' => $this->tipe_diskon,
            'nilai_diskon' => $this->nilai_diskon,
            'jumlah_maksimum_penggunaan' => $this->jumlah_maksimum_penggunaan,
            'jumlah_penggunaan_saat_ini' => $this->jumlah_penggunaan_saat_ini,
            'jumlah_maksimum_penggunaan_per_pengguna' => $this->jumlah_maksimum_penggunaan_per_pengguna,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_berakhir' => $this->tanggal_berakhir,
            'minimum_pembelian' => $this->minimum_pembelian,
            'dapat_digabungkan' => $this->dapat_digabungkan,
            'status_aktif' => $this->status_aktif,
            'id_kategori_produk' => $this->id_kategori_produk,
            'kategori_produk' => $this->whenLoaded('kategori_produk', function () {
                return $this->kategori_produk ? new CategoryProductResource($this->kategori_produk) : null;
            }),
            'id_pembuat' => $this->id_pembuat,
            'pembuat' => $this->whenLoaded('pembuat', function () {
                return $this->pembuat ? new UserResource($this->pembuat) : null;
            }),
            'produk' => $this->whenLoaded('produk', function () {
                return ProductCollection::make($this->produk);
            }),
            'created_at' => $this->dibuat_pada,
            'updated_at' => $this->diperbarui_pada,
        ];
    }
}