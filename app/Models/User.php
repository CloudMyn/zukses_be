<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'nomor_telepon',
        'kata_sandi',
        'tipe_user',
        'status',
        'email_terverifikasi_pada',
        'telepon_terverifikasi_pada',
        'terakhir_login_pada',
        'url_foto_profil',
        'pengaturan',
        'nama_depan',
        'nama_belakang',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'bio',
        'url_media_sosial',
        'bidang_interests',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'kata_sandi',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_terverifikasi_pada' => 'datetime',
        'telepon_terverifikasi_pada' => 'datetime',
        'terakhir_login_pada' => 'datetime',
        'tanggal_lahir' => 'date',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'pengaturan' => 'array',
        'url_media_sosial' => 'array',
        'bidang_interests' => 'array',
    ];

    /**
     * Get the name attribute (combining first and last name)
     */
    public function getNameAttribute()
    {
        return trim($this->nama_depan . ' ' . $this->nama_belakang);
    }

    /**
     * Get the sellers for the user.
     */
    public function sellers()
    {
        return $this->hasMany(Seller::class, 'id_user', 'id');
    }

    /**
     * Get the addresses for the user.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'id_user', 'id');
    }

    /**
     * Get the verifications for the user.
     */
    public function verifications()
    {
        return $this->hasMany(Verification::class, 'id_user', 'id');
    }

    /**
     * Get the devices for the user.
     */
    public function devices()
    {
        return $this->hasMany(Device::class, 'id_user', 'id');
    }
}
