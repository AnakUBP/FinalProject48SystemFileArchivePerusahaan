<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    // ✅ Pastikan Laravel tahu nama tabel sebenarnya
    protected $table = 'cuti';

    protected $fillable = [
        'user_id', 'jenis_cuti_id', 'tanggal_mulai', 'tanggal_selesai', 'alasan', 'status', 'nomor_surat', 'file_surat'
    ];

    // Relasi ke tabel user (setiap cuti dimiliki oleh seorang user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel jenis_cuti (setiap cuti memiliki satu jenis cuti)
    public function jenisCuti()
    {
        return $this->belongsTo(JenisCuti::class);
    }

    // Relasi ke tabel approval_logs (sebuah cuti bisa memiliki banyak approval)
    public function approvalLogs()
    {
        return $this->hasMany(ApprovalLog::class);
    }

    // Relasi ke tabel arsip_cuti (sebuah cuti bisa memiliki arsip)
    public function arsipCuti()
    {
        return $this->hasOne(ArsipCuti::class);
    }
}
