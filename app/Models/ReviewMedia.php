<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewMedia extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_media_ulasan';

    protected $fillable = [
        'id_review',
        'tipe_media',
        'url_media',
        'keterangan_media',
        'urutan_media',
        'dibuat_pada',
    ];

    protected $casts = [
        'id_review' => 'integer',
        'urutan_media' => 'integer',
        'dibuat_pada' => 'datetime',
    ];

    // Define the relationship with the ProductReview model
    public function review(): BelongsTo
    {
        return $this->belongsTo(ProductReview::class, 'id_review', 'id');
    }
}