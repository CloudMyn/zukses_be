<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_master_kota';

    protected $fillable = [
        'provinsi_id',
        'nama',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'provinsi_id' => 'integer',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the Province model
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'provinsi_id', 'id');
    }

    // Define the relationship with the District model
    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'kota_id', 'id');
    }
}