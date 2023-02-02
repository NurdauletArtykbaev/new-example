<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentPushNotification extends Model
{
    protected $fillable = [
        'token_id',
        'user_id',
        'status',
        'pushable_id',
        'pushable_type',
        'fields_json',
    ];

    protected $casts = [
        'fields_json' => 'array'
    ];

    public function pushable() {
        return $this->morphTo();
    }
}
