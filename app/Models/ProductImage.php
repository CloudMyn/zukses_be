<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_gambar_produk';

    protected $fillable = [
        'id_produk',
        'id_harga_varian',
        'url_gambar',
        'alt_text',
        'urutan_gambar',
        'is_gambar_utama',
        'tipe_gambar',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'id_produk' => 'integer',
        'id_harga_varian' => 'integer',
        'urutan_gambar' => 'integer',
        'is_gambar_utama' => 'boolean',
        'tipe_gambar' => 'string',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the Product model
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_produk');
    }

    // Define the relationship with the ProductVariantPrice model
    public function productVariantPrice(): BelongsTo
    {
        return $this->belongsTo(ProductVariantPrice::class, 'id_harga_varian');
    }
}