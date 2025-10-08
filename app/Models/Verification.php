<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'verifikasi_pengguna';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_user',
        'jenis_verifikasi',
        'nilai_verifikasi',
        'kode_verifikasi',
        'kedaluwarsa_pada',
        'telah_digunakan',
        'jumlah_coba',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'kedaluwarsa_pada' => 'datetime',
        'telah_digunakan' => 'boolean',
        'jumlah_coba' => 'integer',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    /**
     * Get the user that owns the verification.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
