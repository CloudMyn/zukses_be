<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderShipping extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_pengiriman_pesanan';

    protected $fillable = [
        'id_pesanan',
        'id_metode_pengiriman',
        'nama_metode_pengiriman',
        'estimasi_pengiriman',
        'biaya_pengiriman',
        'kode_pengiriman',
        'kurir_pengiriman',
        'status_pengiriman',
        'tanggal_pengiriman',
        'tanggal_diterima',
        'bukti_penerimaan',
        'catatan_pengiriman',
        'koordinat_asal',
        'koordinat_tujuan',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'id_pesanan' => 'integer',
        'id_metode_pengiriman' => 'integer',
        'biaya_pengiriman' => 'decimal:2',
        'tanggal_pengiriman' => 'datetime',
        'tanggal_diterima' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'koordinat_asal' => 'array',
        'koordinat_tujuan' => 'array',
    ];

    // Define the relationship with the Order model
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id_pesanan', 'id');
    }
}