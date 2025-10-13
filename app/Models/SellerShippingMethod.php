<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerShippingMethod extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_metode_pengiriman_penjual';

    protected $fillable = [
        'id_seller',
        'id_metode_pengiriman',
        'is_aktif',
        'biaya_tambahan',
        'prioritas',
        'konfigurasi_metode',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'id_seller' => 'integer',
        'id_metode_pengiriman' => 'integer',
        'is_aktif' => 'boolean',
        'biaya_tambahan' => 'decimal:2',
        'prioritas' => 'integer',
        'konfigurasi_metode' => 'array',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the Seller model
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'id_seller', 'id');
    }

    // Define the relationship with the ShippingMethod model
    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class, 'id_metode_pengiriman', 'id');
    }
}