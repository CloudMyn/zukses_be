<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Promosi;
use App\Models\Produk;

class ProdukPromosi extends Model
{
    use HasFactory;

    protected $table = 'tb_produk_promosi';
    
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
        'id_produk',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dibuat_pada' => 'datetime',
    ];

    // Relasi ke Promosi
    public function promosi()
    {
        return $this->belongsTo(Promosi::class, 'id_promosi', 'id');
    }
    
    // Relasi ke Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id');
    }
}