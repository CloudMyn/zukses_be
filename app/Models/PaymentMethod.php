<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_metode_pembayaran';

    protected $fillable = [
        'nama_pembayaran',
        'tipe_pembayaran',
        'provider_pembayaran',
        'logo_pembayaran',
        'deskripsi_pembayaran',
        'biaya_admin_percent',
        'biaya_admin_fixed',
        'minimum_pembayaran',
        'maksimum_pembayaran',
        'is_aktif',
        'urutan_tampilan',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'urutan_tampilan' => 'integer',
        'biaya_admin_percent' => 'decimal:2',
        'biaya_admin_fixed' => 'decimal:2',
        'minimum_pembayaran' => 'decimal:2',
        'maksimum_pembayaran' => 'decimal:2',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];
}