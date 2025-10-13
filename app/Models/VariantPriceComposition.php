<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariantPriceComposition extends Model
{
    use HasFactory;

    protected $table = 'tb_komposisi_harga_varian';

    protected $fillable = [
        'harga_varian_id',
        'nilai_varian_id',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'harga_varian_id' => 'integer',
        'nilai_varian_id' => 'integer',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the ProductVariantPrice model
    public function productVariantPrice(): BelongsTo
    {
        return $this->belongsTo(ProductVariantPrice::class, 'harga_varian_id');
    }

    // Define the relationship with the ProductVariantValue model
    public function productVariantValue(): BelongsTo
    {
        return $this->belongsTo(ProductVariantValue::class, 'nilai_varian_id');
    }
}