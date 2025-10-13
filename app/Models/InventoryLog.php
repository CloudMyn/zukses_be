<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLog extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'log_inventori';

    protected $fillable = [
        'id_produk',
        'id_harga_varian',
        'tipe_transaksi',
        'jumlah_transaksi',
        'stok_sebelum',
        'stok_sesudah',
        'alasan_transaksi',
        'id_operator',
        'catatan_tambahan',
        'dibuat_pada',
    ];

    protected $casts = [
        'id_produk' => 'integer',
        'id_harga_varian' => 'integer',
        'jumlah_transaksi' => 'integer',
        'stok_sebelum' => 'integer',
        'stok_sesudah' => 'integer',
        'id_operator' => 'integer',
        'dibuat_pada' => 'datetime',
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

    // Define the relationship with the User model (operator)
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_operator');
    }
}