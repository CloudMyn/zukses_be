<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk format data laporan penjualan
 */
class SalesReportResource extends JsonResource
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
            'tipe_laporan' => $this->tipe_laporan,
            'periode_laporan' => $this->periode_laporan,
            'data_laporan' => $this->data_laporan,
            'total_transaksi' => $this->total_transaksi,
            'total_nilai_transaksi' => $this->total_nilai_transaksi,
            'dibuat_pada' => $this->dibuat_pada,
            'created_at' => $this->created_at,
        ];
    }
}