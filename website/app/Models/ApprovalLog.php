<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'cuti_id', 'approved_by', 'status', 'catatan', 'approved_at'
    ];

    // Relasi ke tabel cuti (setiap approval terkait dengan satu cuti)
    public function cuti()
    {
        return $this->belongsTo(Cuti::class);
    }

    // Relasi ke tabel user (setiap approval dilakukan oleh seorang user)
    public function user()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
