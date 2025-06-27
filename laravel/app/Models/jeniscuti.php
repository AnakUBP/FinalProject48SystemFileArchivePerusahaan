<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisCuti extends Model
{
    use HasFactory;

    protected $table = 'jenis_cuti';

    protected $fillable = [
        'nama',
        'template_id',
        'keterangan',
        'is_active' // <-- TAMBAHKAN INI
    ];
    public function templates()
    {
        return $this->belongsTo(Templates::class, 'template_id');  
    }
}
