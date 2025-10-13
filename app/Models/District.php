<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    /**
     * Mapping kolom created_at dan updated_at ke bahasa Indonesia.
     */
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'tb_master_kecamatan';

    protected $fillable = [
        'kota_id',
        'nama',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'kota_id' => 'integer',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Define the relationship with the City model
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'kota_id', 'id');
    }

    // Define the relationship with the PostalCode model
    public function postalCodes(): HasMany
    {
        return $this->hasMany(PostalCode::class, 'kecamatan_id', 'id');
    }
}