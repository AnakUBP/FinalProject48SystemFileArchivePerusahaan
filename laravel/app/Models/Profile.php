<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'nama_lengkap',
        'alamat',
        'telepon',
        'tanggal_lahir',
        'jabatan',
        'jenis_kelamin',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class);
    }
}
