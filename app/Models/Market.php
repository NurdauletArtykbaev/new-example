<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory, CrudTrait;
    protected $fillable = [
        'name',
        'number',
        'is_active'
    ];
    protected $casts = [
        'is_active' => 'boolean'
    ];
}
