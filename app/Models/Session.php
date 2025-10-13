<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_sesi_pengguna';

    protected $fillable = [
        'id',
        'id_user',
        'ip_address',
        'user_agent',
        'payload',
        'aktivitas_terakhir',
        'dibuat_pada',
    ];

    protected $casts = [
        'id_user' => 'integer',
        'aktivitas_terakhir' => 'integer',
        'dibuat_pada' => 'datetime',
    ];

    // Define the relationship with the User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}