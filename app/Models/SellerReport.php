<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerReport extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_laporan_penjual';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_penjual',
        'id_admin',
        'jenis_laporan',
        'periode_awal',
        'periode_akhir',
        'data_laporan',
        'ringkasan',
        'tanggal_laporan',
        'status_laporan',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'periode_awal' => 'date',
        'periode_akhir' => 'date',
        'tanggal_laporan' => 'date',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'data_laporan' => 'array',
    ];

    /**
     * Get the seller that owns the report.
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'id_penjual', 'id');
    }

    /**
     * Get the admin that generated the report.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id');
    }
}