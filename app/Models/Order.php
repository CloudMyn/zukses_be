<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_pesanan';

    protected $fillable = [
        'nomor_pesanan',
        'id_customer',
        'id_alamat_pengiriman',
        'status_pesanan',
        'status_pembayaran',
        'total_items',
        'total_berat',
        'subtotal_produk',
        'total_diskon_produk',
        'total_ongkir',
        'total_biaya_layanan',
        'total_pajak',
        'total_pembayaran',
        'metode_pembayaran',
        'bank_pembayaran',
        'va_number',
        'deadline_pembayaran',
        'tanggal_dibayar',
        'no_resi',
        'catatan_pesanan',
        'tanggal_pengiriman',
        'tanggal_selesai',
        'tanggal_dibatalkan',
        'alasan_pembatalan',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'id_customer' => 'integer',
        'id_alamat_pengiriman' => 'integer',
        'total_items' => 'integer',
        'total_berat' => 'decimal:2',
        'subtotal_produk' => 'decimal:2',
        'total_diskon_produk' => 'decimal:2',
        'total_ongkir' => 'decimal:2',
        'total_biaya_layanan' => 'decimal:2',
        'total_pajak' => 'decimal:2',
        'total_pembayaran' => 'decimal:2',
        'deadline_pembayaran' => 'datetime',
        'tanggal_dibayar' => 'datetime',
        'tanggal_pengiriman' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_dibatalkan' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the User model (customer)
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_customer', 'id');
    }

    // Define the relationship with the Address model
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'id_alamat_pengiriman', 'id');
    }

    // Define the relationship with the OrderItem model
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'id_pesanan', 'id');
    }

    // Define the relationship with the OrderStatusHistory model
    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class, 'id_pesanan', 'id');
    }

    // Define the relationship with the PaymentTransaction model
    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class, 'id_pesanan', 'id');
    }

    // Define the relationship with the OrderShipping model
    public function orderShipping(): HasMany
    {
        return $this->hasMany(OrderShipping::class, 'id_pesanan', 'id');
    }
}