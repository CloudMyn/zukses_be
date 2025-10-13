<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_varian_produk';

    protected $fillable = [
        'produk_id',
        'nama_varian',
        'urutan',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'produk_id' => 'integer',
        'urutan' => 'integer',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the Product model
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }

    // Define the relationship with the ProductVariantValue model
    public function values(): HasMany
    {
        return $this->hasMany(ProductVariantValue::class, 'varian_id');
    }
}