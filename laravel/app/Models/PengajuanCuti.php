<?php

// =========================================================
// File: app/Models/PengajuanCuti.php
// =========================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanCuti extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'pengajuan_cuti';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'user_id',
        'jenis_cuti_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'tanda_tangan_path',
        'lampiran_path',
    ];

    /**
     * Relasi: Satu pengajuan dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    /**
     * Relasi: Satu pengajuan memiliki satu JenisCuti.
     */
    public function jenisCuti()
    {
        return $this->belongsTo(JenisCuti::class);
    }

    /**
     * Relasi: Satu pengajuan akan menghasilkan satu SuratCutiResmi.
     */
    public function suratCutiResmi()
    {
        return $this->hasOne(SuratCutiResmi::class);
    }
}
