<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductReview extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_ulasan_produk';

    protected $fillable = [
        'id_produk',
        'id_harga_varian',
        'id_pembeli',
        'id_pesanan',
        'rating_produk',
        'rating_akurasi_produk',
        'rating_kualitas_produk',
        'rating_pengiriman_produk',
        'komentar_ulasan',
        'is_ulasan_anonim',
        'is_ulasan_terverifikasi',
        'is_ditampilkan',
        'jumlah_suka',
        'id_review_parent',
        'tanggal_ulasan',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'id_produk' => 'integer',
        'id_harga_varian' => 'integer',
        'id_pembeli' => 'integer',
        'id_pesanan' => 'integer',
        'rating_produk' => 'integer',
        'rating_akurasi_produk' => 'integer',
        'rating_kualitas_produk' => 'integer',
        'rating_pengiriman_produk' => 'integer',
        'is_ulasan_anonim' => 'boolean',
        'is_ulasan_terverifikasi' => 'boolean',
        'is_ditampilkan' => 'boolean',
        'jumlah_suka' => 'integer',
        'id_review_parent' => 'integer',
        'tanggal_ulasan' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the User model (buyer)
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pembeli', 'id');
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

    // Define the relationship with the Order model
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id_pesanan', 'id');
    }

    // Define the relationship with the ReviewMedia model
    public function media(): HasMany
    {
        return $this->hasMany(ReviewMedia::class, 'id_review', 'id');
    }

    // Define the relationship with the ReviewVote model
    public function votes(): HasMany
    {
        return $this->hasMany(ReviewVote::class, 'id_review', 'id');
    }
}