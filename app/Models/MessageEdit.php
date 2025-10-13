<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageEdit extends Model
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
    protected $table = 'tb_chat_edit_pesan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_pesan',
        'id_user',
        'isi_pesan_lama',
        'isi_pesan_baru',
        'tanggal_edit',
        'alasan_edit',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_edit' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    /**
     * Get the message that was edited.
     */
    public function message()
    {
        return $this->belongsTo(ChatMessage::class, 'id_pesan', 'id');
    }

    /**
     * Get the user who edited the message.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}