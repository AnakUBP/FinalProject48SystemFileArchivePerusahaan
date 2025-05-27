<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisCuti extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'keterangan'
    ];

    // Relasi ke tabel cuti (jenis cuti bisa digunakan oleh banyak cuti)
    public function cutis()
    {
        return $this->hasMany(Cuti::class, 'jenis_cuti_id');
    }
}
