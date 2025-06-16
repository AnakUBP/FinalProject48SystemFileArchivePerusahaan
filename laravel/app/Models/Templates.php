<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Templates extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_template',
        'kategori',
        'file_path',
        'versi',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];
    public function scopeActive($query)
    {
        return $query->where('active');
    }
}
