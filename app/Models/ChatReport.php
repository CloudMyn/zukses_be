<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatReport extends Model
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
    protected $table = 'tb_chat_laporan_percakapan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_obrolan',
        'id_pelapor',
        'id_pelanggar',
        'jenis_pelanggaran',
        'deskripsi_pelanggaran',
        'bukti_pelanggaran',
        'status_laporan',
        'tanggal_laporan',
        'tanggal_review',
        'catatan_review',
        'id_admin_reviewer',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_laporan' => 'datetime',
        'tanggal_review' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    /**
     * Get the conversation that was reported.
     */
    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class, 'id_obrolan', 'id');
    }

    /**
     * Get the user who reported.
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'id_pelapor', 'id');
    }

    /**
     * Get the user who was reported.
     */
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'id_pelanggar', 'id');
    }

    /**
     * Get the admin who reviewed the report.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'id_admin_reviewer', 'id');
    }
}