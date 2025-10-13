<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchHistory extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_riwayat_pencarian';

    protected $fillable = [
        'id_user',
        'kata_pencarian',
        'jumlah_hasil',
        'ip_address',
        'dibuat_pada',
    ];

    protected $casts = [
        'id_user' => 'integer',
        'jumlah_hasil' => 'integer',
        'dibuat_pada' => 'datetime',
    ];

    // Define the relationship with the User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}