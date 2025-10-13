<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotification extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_notifikasi_pengguna';

    protected $fillable = [
        'id_user',
        'tipe_notifikasi',
        'judul_notifikasi',
        'isi_notifikasi',
        'data_notifikasi',
        'url_redirect',
        'gambar_notifikasi',
        'is_dibaca',
        'is_dikirim_push',
        'is_dikirim_email',
        'is_dikirim_sms',
        'tanggal_dibaca',
        'kadaluarsa_pada',
        'dibuat_pada',
    ];

    protected $casts = [
        'id_user' => 'integer',
        'is_dibaca' => 'boolean',
        'is_dikirim_push' => 'boolean',
        'is_dikirim_email' => 'boolean',
        'is_dikirim_sms' => 'boolean',
        'tanggal_dibaca' => 'datetime',
        'kadaluarsa_pada' => 'datetime',
        'dibuat_pada' => 'datetime',
        'data_notifikasi' => 'array',
    ];

    // Define the relationship with the User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}