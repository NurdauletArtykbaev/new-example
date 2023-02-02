<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'date',
        'day_of_week',
        'day_number_in_week'
    ];
}
