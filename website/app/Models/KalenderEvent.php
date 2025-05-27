<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KalenderEvent extends Model
{
    protected $table = 'kalender_event';

    protected $fillable = [
        'title', 'start_date', 'end_date', 'cuti_id'
    ];

    public function cuti()
    {
        return $this->belongsTo(Cuti::class);
    }
}
