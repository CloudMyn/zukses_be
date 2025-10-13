<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageReaction extends Model
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
    protected $table = 'tb_chat_reaksi_pesan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_pesan',
        'id_user',
        'jenis_reaksi',
        'deskripsi_reaksi',
        'tanggal_reaksi',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_reaksi' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    /**
     * Get the message this reaction belongs to.
     */
    public function message()
    {
        return $this->belongsTo(ChatMessage::class, 'id_pesan', 'id');
    }

    /**
     * Get the user who reacted.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}