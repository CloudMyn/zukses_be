<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatParticipant extends Model
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
    protected $table = 'tb_chat_peserta_percakapan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_obrolan',
        'id_user',
        'status_partisipan',
        'is_admin',
        'is_muted',
        'tanggal_join',
        'tanggal_keluar',
        'catatan_partisipan',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_join' => 'datetime',
        'tanggal_keluar' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'is_admin' => 'boolean',
        'is_muted' => 'boolean',
    ];

    /**
     * Get the user that participates in the conversation.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    /**
     * Get the conversation the user participates in.
     */
    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class, 'id_obrolan', 'id');
    }
}