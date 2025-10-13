<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageAttachment extends Model
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
    protected $table = 'tb_chat_lampiran_pesan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_pesan',
        'nama_file',
        'path_file',
        'url_file',
        'jenis_file',
        'ukuran_file',
        'mime_type',
        'deskripsi_file',
        'is_thumbnail',
        'urutan_tampilan',
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
        'is_thumbnail' => 'boolean',
        'ukuran_file' => 'integer',
        'urutan_tampilan' => 'integer',
    ];

    /**
     * Get the message this attachment belongs to.
     */
    public function message()
    {
        return $this->belongsTo(ChatMessage::class, 'id_pesan', 'id');
    }
}