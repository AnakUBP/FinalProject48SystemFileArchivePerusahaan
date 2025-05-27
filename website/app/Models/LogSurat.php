<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogSurat extends Model
{
    use HasFactory;

    protected $table = 'log_surat';
    public $timestamps = false;

    protected $fillable = [
        'jenis', 'cuti_id', 'nomor_surat', 'kategori', 'status', 'catatan', 'waktu'
    ];

    public function cuti()
    {
        return $this->belongsTo(Cuti::class);
    }
}
