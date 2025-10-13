<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewVote extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_suara_ulasan';

    protected $fillable = [
        'id_review',
        'id_user',
        'tipe_vote',
        'dibuat_pada',
    ];

    protected $casts = [
        'id_review' => 'integer',
        'id_user' => 'integer',
        'dibuat_pada' => 'datetime',
    ];

    // Define the relationship with the ProductReview model
    public function review(): BelongsTo
    {
        return $this->belongsTo(ProductReview::class, 'id_review', 'id');
    }

    // Define the relationship with the User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}