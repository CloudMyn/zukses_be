<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariantShippingInfo extends Model
{
    use HasFactory;

    protected $table = 'tb_pengiriman_varian_produk';

    protected $fillable = [
        'harga_varian_id',
        'berat',
        'panjang',
        'lebar',
        'tinggi',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'harga_varian_id' => 'integer',
        'berat' => 'decimal:2',
        'panjang' => 'decimal:2',
        'lebar' => 'decimal:2',
        'tinggi' => 'decimal:2',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the ProductVariantPrice model
    public function productVariantPrice(): BelongsTo
    {
        return $this->belongsTo(ProductVariantPrice::class, 'harga_varian_id');
    }
}