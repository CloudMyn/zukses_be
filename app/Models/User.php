<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

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
        'username', // Required
        'email', // Optional but if provided must be unique
        'nomor_telepon', // Optional but if provided must be unique
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
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'tipe_user' => $this->tipe_user,
            'username' => $this->username,
            'email' => $this->email,
        ];
    }

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

    /**
     * Get the cart for the user.
     */
    public function carts()
    {
        return $this->hasMany(Cart::class, 'id_user', 'id');
    }

    /**
     * Get the orders for the user (as customer).
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_customer', 'id');
    }

    /**
     * Get the reviews for the user.
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'id_pembeli', 'id');
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(UserNotification::class, 'id_user', 'id');
    }

    /**
     * Get the activities for the user.
     */
    public function activities()
    {
        return $this->hasMany(UserActivity::class, 'id_user', 'id');
    }

    /**
     * Get the search history for the user.
     */
    public function searchHistory()
    {
        return $this->hasMany(SearchHistory::class, 'id_user', 'id');
    }

    /**
     * Get the admin profile for the user.
     */
    public function adminProfile()
    {
        return $this->hasOne(AdminUser::class, 'id_user', 'id');
    }

    /**
     * Get the sessions for the user.
     */
    public function sessions()
    {
        return $this->hasMany(Session::class, 'id_user', 'id');
    }

    /**
     * Get the chat conversations where the user is the owner.
     */
    public function ownedConversations()
    {
        return $this->hasMany(ChatConversation::class, 'owner_user_id', 'id');
    }

    /**
     * Get the chat participants for the user.
     */
    public function chatParticipants()
    {
        return $this->hasMany(ChatParticipant::class, 'user_id', 'id');
    }

    /**
     * Get the messages sent by the user.
     */
    public function sentMessages()
    {
        return $this->hasMany(ChatMessage::class, 'pengirim_user_id', 'id');
    }

    /**
     * Get the message statuses for the user.
     */
    public function messageStatuses()
    {
        return $this->hasMany(MessageStatus::class, 'user_id', 'id');
    }

    /**
     * Get the message reactions by the user.
     */
    public function messageReactions()
    {
        return $this->hasMany(MessageReaction::class, 'user_id', 'id');
    }

    /**
     * Get the message edits by the user.
     */
    public function messageEdits()
    {
        return $this->hasMany(MessageEdit::class, 'editor_id', 'id');
    }

    /**
     * Get the reports made by the user.
     */
    public function reports()
    {
        return $this->hasMany(ChatReport::class, 'reporter_id', 'id');
    }
}
