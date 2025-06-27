<?php
// app/Models/ActivityLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'loggable_id',
        'loggable_type',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\Users::class, 'user_id');
    }
    

    public function loggable()
    {
        return $this->morphTo();
    }
}

