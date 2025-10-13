<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
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
    protected $table = 'tb_alamat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_user',
        'label_alamat',
        'nama_penerima',
        'nomor_telepon_penerima',
        'alamat_lengkap',
        'id_provinsi',
        'nama_provinsi',
        'id_kabupaten',
        'nama_kabupaten',
        'id_kecamatan',
        'nama_kecamatan',
        'id_kelurahan',
        'nama_kelurahan',
        'kode_pos',
        'latitude',
        'longitude',
        'adalah_alamat_utama',
        'tipe_alamat',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id_user' => 'integer',
        'id_provinsi' => 'integer',
        'id_kabupaten' => 'integer',
        'id_kecamatan' => 'integer',
        'id_kelurahan' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'adalah_alamat_utama' => 'boolean',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
