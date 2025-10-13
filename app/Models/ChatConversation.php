<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
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
    protected $table = 'tb_chat_percakapan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_obrolan',
        'deskripsi_obrolan',
        'jenis_obrolan',
        'is_group',
        'is_active',
        'jumlah_partisipan',
        'id_pembuat',
        'tanggal_pembuatan',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_pembuatan' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'is_group' => 'boolean',
        'is_active' => 'boolean',
        'jumlah_partisipan' => 'integer',
    ];

    /**
     * Get the user who created the conversation.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'id_pembuat', 'id');
    }

    /**
     * Get the participants of the conversation.
     */
    public function participants()
    {
        return $this->hasMany(ChatParticipant::class, 'id_obrolan', 'id');
    }

    /**
     * Get the messages in the conversation.
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'id_obrolan', 'id');
    }
}