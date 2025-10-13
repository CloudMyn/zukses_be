<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_aktivitas_pengguna';

    protected $fillable = [
        'id_user',
        'sesi_id',
        'tipe_aktivitas',
        'data_aktivitas',
        'ip_address',
        'user_agent',
        'referrer',
        'halaman_asal',
        'dibuat_pada',
    ];

    protected $casts = [
        'id_user' => 'integer',
        'data_aktivitas' => 'array',
        'dibuat_pada' => 'datetime',
    ];

    // Define the relationship with the User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}