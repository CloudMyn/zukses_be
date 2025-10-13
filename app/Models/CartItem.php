<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_item_keranjang';

    protected $fillable = [
        'id_cart',
        'id_produk',
        'id_harga_varian',
        'kuantitas',
        'harga_satuan',
        'harga_total',
        'diskon_item',
        'catatan_item',
        'gambar_produk',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'id_cart' => 'integer',
        'id_produk' => 'integer',
        'id_harga_varian' => 'integer',
        'kuantitas' => 'integer',
        'harga_satuan' => 'decimal:2',
        'harga_total' => 'decimal:2',
        'diskon_item' => 'decimal:2',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the Cart model
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'id_cart', 'id');
    }

    // Define the relationship with the Product model
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id');
    }

    // Define the relationship with the ProductVariantPrice model
    public function productVariantPrice(): BelongsTo
    {
        return $this->belongsTo(ProductVariantPrice::class, 'id_harga_varian', 'id');
    }
}