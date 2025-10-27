<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLog extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_log_pembayaran';

    protected $fillable = [
        'id_transaksi_pembayaran',
        'id_user',
        'aksi_log',
        'deskripsi_log',
        'data_sebelumnya',
        'data_perubahan',
        'ip_address',
        'user_agent',
        'dibuat_pada',
    ];

    protected $casts = [
        'id_transaksi_pembayaran' => 'integer',
        'id_user' => 'integer',
        'data_sebelumnya' => 'array',
        'data_perubahan' => 'array',
        'dibuat_pada' => 'datetime',
    ];

    // Define the relationship with the PaymentTransaction model
    public function paymentTransaction(): BelongsTo
    {
        return $this->belongsTo(PaymentTransaction::class, 'id_transaksi_pembayaran', 'id');
    }

    // Define the relationship with the User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}