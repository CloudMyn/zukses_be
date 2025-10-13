<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariantPrice extends Model
{
    use HasFactory;

    protected $table = 'tb_harga_varian_produk';

    protected $fillable = [
        'produk_id',
        'gambar',
        'harga',
        'stok',
        'kode_varian',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'produk_id' => 'integer',
        'harga' => 'decimal:2',
        'stok' => 'integer',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the Product model
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }

    // Define the relationship with the VariantPriceComposition model
    public function variantPriceCompositions(): HasMany
    {
        return $this->hasMany(VariantPriceComposition::class, 'harga_varian_id');
    }

    // Define the relationship with the VariantShippingInfo model
    public function variantShippingInfo(): HasMany
    {
        return $this->hasMany(VariantShippingInfo::class, 'harga_varian_id');
    }
}