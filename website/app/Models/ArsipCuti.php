<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipCuti extends Model
{
    use HasFactory;

    protected $fillable = [
        'cuti_id', 'nomor_surat', 'file_surat', 'tanggal_arsip'
    ];

    // Relasi ke tabel cuti (setiap arsip terkait dengan satu cuti)
    public function cuti()
    {
        return $this->belongsTo(Cuti::class);
    }
}
