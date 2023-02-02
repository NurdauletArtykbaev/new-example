<?php

namespace App\Models;

use App\Facades\DateFormatter;
use App\Helpers\NotificationHelper;
use App\Helpers\Status;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use phpseclib3\File\ASN1\Maps\TBSCertificate;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'personal_number',
        'is_online'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getShiftAttribute()
    {
        return $this->shifts()->first();
    }

    public function findForPassport($value)
    {
        return User::query()->where('personal_number', $value)->first();
    }

    public function isOnline()
    {
        return $this->is_online;
    }

    public function workEntries()
    {
        return $this->hasMany(WorkEntry::class);
    }

    public function store()
    {
        return $this->belongsToMany(Store::class, 'user_store');
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function orders()
    {
        return $this->hasMany(UserOrder::class);
    }

    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'user_shift');
    }

    public function dates()
    {
        return $this->belongsToMany(Date::class, 'schedules')->withPivot(['shift_id']);
    }

    public function sentPushNotifications()
    {
        return $this->hasMany(SentPushNotification::class);
    }

    public function dates_filed()
    {
        return $this->dates();
    }

    public function unreadSentPushNotifications()
    {
        return $this->sentPushNotifications()
            ->where('status', NotificationHelper::STATUS_UNREAD)
            ->where('pushable_type', '=', Notification::class);
    }
}
