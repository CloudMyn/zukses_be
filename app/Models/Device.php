<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
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
    protected $table = 'tb_perangkat_pengguna';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_user',
        'device_id',
        'device_type',
        'device_name',
        'operating_system',
        'app_version',
        'push_token',
        'adalah_device_terpercaya',
        'terakhir_aktif_pada',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'adalah_device_terpercaya',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'is_trusted',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'adalah_device_terpercaya' => 'boolean',
        'terakhir_aktif_pada' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    /**
     * Get the is_trusted attribute (alias for adalah_device_terpercaya).
     */
    public function getIsTrustedAttribute()
    {
        return $this->adalah_device_terpercaya;
    }

    /**
     * Set the is_trusted attribute (alias for adalah_device_terpercaya).
     */
    public function setIsTrustedAttribute($value)
    {
        $this->attributes['adalah_device_terpercaya'] = $value;
    }

    /**
     * Get the last_used_at attribute (alias for terakhir_aktif_pada).
     */
    public function getLastUsedAtAttribute()
    {
        return $this->terakhir_aktif_pada;
    }

    /**
     * Get the user that owns the device.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
