<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'perangkat_pengguna';

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
     * Get the user that owns the device.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
