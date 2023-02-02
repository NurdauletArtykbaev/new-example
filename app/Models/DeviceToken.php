<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    protected $fillable = [
        'user_id',
        'value',
        'installation_id',
        'driver',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
