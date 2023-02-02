<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkEntry extends Model
{
    public $timestamps = false;

    protected $dates = [
        'entered_at',
        'exited_at',
    ];

    protected $fillable = [
        'entered_at',
        'exited_at',
        'seconds_worked',
        'user_id'
    ];
}
