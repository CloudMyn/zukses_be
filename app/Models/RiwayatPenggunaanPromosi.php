<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Promosi;
use App\Models\User;
use App\Models\Order;

class RiwayatPenggunaanPromosi extends Model
{
    use HasFactory;

    protected $table = 'tb_riwayat_penggunaan_promosi';
    
    // Mapping kolom created_at dan updated_at ke bahasa Indonesia.
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = null; // This table doesn't have updated_at column

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_promosi',
        'id_pengguna',
        'id_pesanan',
        'tanggal_penggunaan',
        'jumlah_diskon_diterapkan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_penggunaan' => 'datetime',
        'jumlah_diskon_diterapkan' => 'decimal:2',
        'dibuat_pada' => 'datetime',
    ];

    // Relasi ke Promosi
    public function promosi()
    {
        return $this->belongsTo(Promosi::class, 'id_promosi', 'id');
    }
    
    // Relasi ke Pengguna
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id');
    }
    
    // Relasi ke Pesanan
    public function pesanan()
    {
        return $this->belongsTo(Order::class, 'id_pesanan', 'id');
    }
}