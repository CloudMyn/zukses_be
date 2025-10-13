<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductShippingInfo extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'info_pengiriman_produk';

    protected $fillable = [
        'id_produk',
        'id_kota_asal',
        'nama_kota_asal',
        'estimasi_pengiriman',
        'berat_pengiriman',
        'dimensi_pengiriman',
        'biaya_pengemasan',
        'is_gratis_ongkir',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'id_produk' => 'integer',
        'id_kota_asal' => 'integer',
        'estimasi_pengiriman' => 'array',
        'berat_pengiriman' => 'decimal:2',
        'dimensi_pengiriman' => 'array',
        'biaya_pengemasan' => 'decimal:2',
        'is_gratis_ongkir' => 'boolean',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the Product model
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_produk');
    }

    // Define the relationship with the City model
    public function originCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'id_kota_asal');
    }
}