<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_transaksi_pembayaran';

    protected $fillable = [
        'id_pesanan',
        'id_metode_pembayaran',
        'reference_id',
        'jumlah_pembayaran',
        'status_transaksi',
        'channel_pembayaran',
        'va_number',
        'qr_code',
        'deep_link',
        'tanggal_kadaluarsa',
        'tanggal_bayar',
        'response_gateway',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'id_pesanan' => 'integer',
        'id_metode_pembayaran' => 'integer',
        'jumlah_pembayaran' => 'decimal:2',
        'tanggal_kadaluarsa' => 'datetime',
        'tanggal_bayar' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'response_gateway' => 'array',
    ];

    // Define the relationship with the Order model
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id_pesanan', 'id');
    }

    // Define the relationship with the PaymentMethod model
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'id_metode_pembayaran', 'id');
    }
}