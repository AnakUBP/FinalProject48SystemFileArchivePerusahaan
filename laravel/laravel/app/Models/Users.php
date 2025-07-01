<?php

namespace App\Models;

// FIX: Menggunakan 'use App\Models\Profile;' bukan dari Symfony
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash; // FIX: Import class Hash
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
// FIX: Nama class disarankan menggunakan bentuk tunggal (singular) -> User
// Namun saya tetap menggunakan Users sesuai permintaan Anda
class Users extends Authenticatable implements CanResetPassword // <-- 2. Implementasikan ini
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active',      
        'last_seen', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Relasi one-to-one ke model Profile.
     */
    public function profile()
    {
        // FIX: Mendefinisikan foreign key 'users_id' secara eksplisit
        return $this->hasOne(Profile::class, 'users_id');
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active'     => 'boolean',
        'last_seen' => 'datetime',
        'password' => 'hashed', // FIX: Cara modern untuk casting password
    ];

    /**
     * Mengecek apakah user sedang online.
     */
    public function isOnline()
    {
        // Logika ini sudah benar
        return $this->last_seen && $this->last_seen->gt(now()->subMinutes(5));
    }
}
