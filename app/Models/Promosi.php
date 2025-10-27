<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\RiwayatPenggunaanPromosi;

class Promosi extends Model
{
    use HasFactory;

    protected $table = 'tb_promosi';
    
    // Mapping kolom created_at dan updated_at ke bahasa Indonesia.
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_promosi',
        'nama_promosi',
        'deskripsi',
        'jenis_promosi',
        'tipe_diskon',
        'nilai_diskon',
        'jumlah_maksimum_penggunaan',
        'jumlah_penggunaan_saat_ini',
        'jumlah_maksimum_penggunaan_per_pengguna',
        'tanggal_mulai',
        'tanggal_berakhir',
        'minimum_pembelian',
        'id_kategori_produk',
        'dapat_digabungkan',
        'status_aktif',
        'id_pembuat',
        'id_pembaharu_terakhir',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_berakhir' => 'datetime',
        'nilai_diskon' => 'decimal:2',
        'minimum_pembelian' => 'decimal:2',
        'jumlah_maksimum_penggunaan' => 'integer',
        'jumlah_penggunaan_saat_ini' => 'integer',
        'jumlah_maksimum_penggunaan_per_pengguna' => 'integer',
        'dapat_digabungkan' => 'boolean',
        'status_aktif' => 'boolean',
    ];

    // Relasi ke User pembuat
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'id_pembuat', 'id');
    }
    
    // Relasi ke User pembaharu terakhir
    public function pembaharu()
    {
        return $this->belongsTo(User::class, 'id_pembaharu_terakhir', 'id');
    }
    
    // Relasi ke Kategori Produk
    public function kategori_produk()
    {
        return $this->belongsTo(CategoryProduct::class, 'id_kategori_produk', 'id');
    }
    
    // Relasi ke Produk (many-to-many melalui pivot table)
    public function produk()
    {
        return $this->belongsToMany(Product::class, 'tb_produk_promosi', 'id_promosi', 'id_produk');
    }
    
    // Relasi ke Riwayat Penggunaan Promosi
    public function riwayat_penggunaan()
    {
        return $this->hasMany(RiwayatPenggunaanPromosi::class, 'id_promosi', 'id');
    }
    
    // Method untuk mengecek apakah promosi aktif
    public function isActive()
    {
        // Check if promotion is manually active
        if (!$this->status_aktif) {
            return false;
        }
        
        // Check date boundaries with a small tolerance for microsecond differences
        $currentDateTime = now();
        
        // Ensure the current time is within the start and end range (inclusive)
        // We're using gte (greater than or equal) and lte (less than or equal) to be more explicit
        $afterStart = $currentDateTime->gte($this->tanggal_mulai);
        $beforeEnd = $currentDateTime->lte($this->tanggal_berakhir);
        
        if (!$afterStart || !$beforeEnd) {
            return false;
        }
        
        // Check usage limits: if jumlah_maksimum_penggunaan is 0, it means unlimited
        if ($this->jumlah_maksimum_penggunaan > 0 && 
            $this->jumlah_penggunaan_saat_ini >= $this->jumlah_maksimum_penggunaan) {
            return false;
        }
        
        return true;
    }
    
    // Method untuk menghitung total diskon
    public function hitungDiskon($jumlah_pembelian)
    {
        switch ($this->tipe_diskon) {
            case 'PERSEN':
                return ($jumlah_pembelian * $this->nilai_diskon) / 100;
            case 'NOMINAL':
                return min($this->nilai_diskon, $jumlah_pembelian);
            default:
                return 0;
        }
    }
}