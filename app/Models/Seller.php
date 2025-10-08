<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'penjual';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_user',
        'nama_toko',
        'slug_toko',
        'deskripsi_toko',
        'logo_toko',
        'banner_toko',
        'nomor_ktp',
        'foto_ktp',
        'nomor_npwp',
        'foto_npwp',
        'jenis_usaha',
        'status_verifikasi',
        'tanggal_verifikasi',
        'id_verifikator',
        'catatan_verifikasi',
        'rating_toko',
        'total_penjualan',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_verifikasi' => 'date',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'rating_toko' => 'decimal:2',
        'total_penjualan' => 'integer',
    ];

    /**
     * Get the user that owns the seller.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    /**
     * Get the verifiers for the seller.
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'id_verifikator', 'id');
    }
}
