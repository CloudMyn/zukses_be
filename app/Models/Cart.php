<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_keranjang_belanja';

    protected $fillable = [
        'id_user',
        'session_id',
        'id_seller',
        'total_items',
        'total_berat',
        'total_harga',
        'total_diskon',
        'is_cart_aktif',
        'kadaluarsa_pada',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'id_user' => 'integer',
        'id_seller' => 'integer',
        'total_items' => 'integer',
        'total_berat' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'total_diskon' => 'decimal:2',
        'is_cart_aktif' => 'boolean',
        'kadaluarsa_pada' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Define the relationship with the Seller model
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'id_seller', 'id');
    }

    // Define the relationship with the CartItem model
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class, 'id_cart', 'id');
    }
}