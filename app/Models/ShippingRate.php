<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_tarif_pengiriman';

    protected $fillable = [
        'id_metode_pengiriman',
        'id_asal',
        'id_tujuan',
        'tipe_jarak',
        'min_berat',
        'max_berat',
        'min_nilai',
        'max_nilai',
        'biaya_dasar',
        'biaya_per_kg',
        'estimasi_hari',
        'is_aktif',
        'prioritas',
        'keterangan_tarif',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'id_metode_pengiriman' => 'integer',
        'id_asal' => 'integer',
        'id_tujuan' => 'integer',
        'min_berat' => 'decimal:2',
        'max_berat' => 'decimal:2',
        'min_nilai' => 'decimal:2',
        'max_nilai' => 'decimal:2',
        'biaya_dasar' => 'decimal:2',
        'biaya_per_kg' => 'decimal:2',
        'estimasi_hari' => 'integer',
        'is_aktif' => 'boolean',
        'prioritas' => 'integer',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];
}