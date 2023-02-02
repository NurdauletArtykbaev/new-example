<?php

namespace App\Models;

use App\Traits\Pushable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use CrudTrait, Pushable;

    public $translatable = ['text', 'subject'];

    protected $fillable = [
        'text',
        'subject',
        'description',
        'send_sms',
        'key',
    ];

    //SCOPES
    public function scopeFindByKey($query, string $key)
    {
        return $query->where('key', $key)->first();
    }

}
