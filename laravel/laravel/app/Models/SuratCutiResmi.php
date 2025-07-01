<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratCutiResmi extends Model
{
    use HasFactory;

    protected $table = 'surat_cuti_resmi';

    /**
     * The attributes that are mass assignable.
     * PASTIKAN SEMUA KOLOM INI ADA UNTUK MENGIZINKAN UPDATE.
     */
    protected $fillable = [
        'pengajuan_cuti_id',
        'status',
        'approved_by',
        'approved_at',
        'catatan_approval',
        'nomor_surat',
        'file_hasil_path',
    ];
    
    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Relasi: Satu surat resmi terhubung ke satu PengajuanCuti.
     */
    public function pengajuanCuti()
    {
        return $this->belongsTo(PengajuanCuti::class);
    }

    /**
     * Relasi: Satu surat resmi disetujui oleh satu User (Admin).
     */
    public function approver()
    {
        // Gunakan nama model 'Users' sesuai dengan yang Anda miliki
        return $this->belongsTo(Users::class, 'approved_by');
    }
}
