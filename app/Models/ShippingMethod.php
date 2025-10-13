<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_metode_pengiriman';

    protected $fillable = [
        'nama_metode',
        'kode_metode',
        'deskripsi_metode',
        'gambar_metode',
        'is_aktif',
        'urutan_tampilan',
        'tipe_pengiriman',
        'min_berat',
        'max_berat',
        'min_nilai',
        'max_nilai',
        'biaya_minimum',
        'biaya_maximum',
        'konfigurasi_metode',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'urutan_tampilan' => 'integer',
        'min_berat' => 'decimal:2',
        'max_berat' => 'decimal:2',
        'min_nilai' => 'decimal:2',
        'max_nilai' => 'decimal:2',
        'biaya_minimum' => 'decimal:2',
        'biaya_maximum' => 'decimal:2',
        'konfigurasi_metode' => 'array',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];
}