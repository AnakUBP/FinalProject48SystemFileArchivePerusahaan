<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    protected $casts = [
        'aktif' => 'boolean',
        'last_seen' => 'datetime',
    ];

    public function isOnline()
    {
        return $this->last_seen && $this->last_seen->gt(now()->subMinutes(5));
    }
}
