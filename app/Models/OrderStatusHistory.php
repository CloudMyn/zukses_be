<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_riwayat_status_pesanan';

    protected $fillable = [
        'id_pesanan',
        'status_sebelumnya',
        'status_baru',
        'alasan_perubahan',
        'catatan_perubahan',
        'diubah_oleh_id',
        'diubah_oleh_tipe',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'id_pesanan' => 'integer',
        'diubah_oleh_id' => 'integer',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the Order model
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id_pesanan', 'id');
    }

    // Define the relationship with the User model (changed by)
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diubah_oleh_id', 'id');
    }
}