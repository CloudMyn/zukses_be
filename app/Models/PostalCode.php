<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostalCode extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_master_kode_pos';

    protected $fillable = [
        'kecamatan_id',
        'kode',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'kecamatan_id' => 'integer',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the District model
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'kecamatan_id', 'id');
    }
}