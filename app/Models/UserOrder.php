<?php

namespace App\Models;

use App\Helpers\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserOrder extends Model
{
    protected $fillable = [
        'status',
        'user_id',
        'order_id',
        'reserve_id',
        'items',
        'order_data',
        'finished_at',
        'priority'
    ];

    protected $dates = [
        'finished_at'
    ];

    protected $casts = [
        'items'         => 'array',
        'order_data'    => 'array'
    ];

    public function getTimeSpentAttribute() {
        $diff = Carbon::parse($this->finished_at)->diff($this->created_at);

        return "$diff->h:$diff->i:$diff->s";
    }

    public function scopeFinished($builder) {
        return $builder->where('status', Status::FINISHED);
    }

    public function scopeOrderIdsIn($builder, $ids) {
        return $builder->whereIn('order_id', $ids);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
