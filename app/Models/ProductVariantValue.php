<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantValue extends Model
{
    use HasFactory;

    protected $table = 'tb_nilai_varian_produk';

    protected $fillable = [
        'varian_id',
        'nilai',
        'urutan',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'varian_id' => 'integer',
        'urutan' => 'integer',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the ProductVariant model
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'varian_id');
    }
}