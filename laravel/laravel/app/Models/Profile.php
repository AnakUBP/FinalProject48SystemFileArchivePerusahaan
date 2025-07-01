<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * PASTIKAN SEMUA KOLOM INI ADA.
     */
    protected $fillable = [
        'users_id',
        'nama_lengkap',
        'jabatan',
        'telepon',
        'alamat',
        'tanda_tangan',
        // Tambahkan sisa_kuota_cuti jika Anda mengelolanya di sini juga
        'sisa_kuota_cuti', 
    ];

    /**
     * Relasi ke model Users.
     */
    public function user()
    {
        return $this->belongsTo(Users::class, 'users_id');
    }
}
