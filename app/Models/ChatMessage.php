<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
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
    protected $table = 'tb_chat_pesan_chat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_obrolan',
        'id_pengirim',
        'isi_pesan',
        'jenis_pesan',
        'id_pesan_induk', // For replies
        'tanggal_pesan',
        'dibaca_pada',
        'ditarik_pada', // When message is deleted/withdrawn
        'jumlah_baca',
        'jumlah_diteruskan',
        'metadata',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_pesan' => 'datetime',
        'dibaca_pada' => 'datetime',
        'ditarik_pada' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'jumlah_baca' => 'integer',
        'jumlah_diteruskan' => 'integer',
        'metadata' => 'array',
    ];

    /**
     * Get the sender of the message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'id_pengirim', 'id');
    }

    /**
     * Get the conversation the message belongs to.
     */
    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class, 'id_obrolan', 'id');
    }

    /**
     * Get the parent message if this is a reply.
     */
    public function parentMessage()
    {
        return $this->belongsTo(ChatMessage::class, 'id_pesan_induk', 'id');
    }

    /**
     * Get all replies to this message.
     */
    public function replies()
    {
        return $this->hasMany(ChatMessage::class, 'id_pesan_induk', 'id');
    }

    /**
     * Get all message statuses.
     */
    public function statuses()
    {
        return $this->hasMany(MessageStatus::class, 'id_pesan', 'id');
    }
}