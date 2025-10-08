<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data laporan penjual
 */
class SellerReportResource extends JsonResource
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
            'tipe_laporan' => $this->tipe_laporan,
            'periode_laporan' => $this->periode_laporan,
            'total_pesanan' => $this->total_pesanan,
            'total_penjualan' => $this->total_penjualan,
            'total_pendapatan' => $this->total_pendapatan,
            'total_ongkir' => $this->total_ongkir,
            'total_komisi_platform' => $this->total_komisi_platform,
            'total_bersih' => $this->total_bersih,
            'produk_terlaris' => $this->produk_terlaris,
            'pembelian_terbanyak' => $this->pembelian_terbanyak,
            'rating_rata_rata' => $this->rating_rata_rata,
            'dibuat_pada' => $this->dibuat_pada,
            'created_at' => $this->created_at,
        ];
    }
}