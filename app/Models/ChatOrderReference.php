<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatOrderReference extends Model
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
    protected $table = 'tb_chat_referensi_order_chat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_obrolan',
        'id_order',
        'id_pesan',
        'keterangan_referensi',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    /**
     * Get the conversation this reference belongs to.
     */
    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class, 'id_obrolan', 'id');
    }

    /**
     * Get the order this reference points to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id');
    }

    /**
     * Get the message this reference is attached to.
     */
    public function message()
    {
        return $this->belongsTo(ChatMessage::class, 'id_pesan', 'id');
    }
}