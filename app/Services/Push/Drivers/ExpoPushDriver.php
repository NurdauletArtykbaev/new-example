<?php

namespace App\Services\Push\Drivers;

use App\Helpers\DeviceTokenHelper;
use App\Services\Push\Classes\PushNotification;
use App\Services\Push\Contracts\PushDriverInterface;
use Illuminate\Support\Facades\Http;

class ExpoPushDriver implements PushDriverInterface
{
    const URL = 'https://exp.host/--/api/v2/push/send/';

    public function notify(PushNotification $push)
    {
        $data = $this->prepare($push);
        $response = Http::post(self::URL, $data)->json();
        return [ $response, DeviceTokenHelper::DRIVER_FIREBASE];
    }

    public function prepare(PushNotification $push) {
        $tokens = $push->getReceiver()->deviceTokens()->where('driver', DeviceTokenHelper::DRIVER_EXPO)->get()->pluck('value')->toArray();

        return [
            'to'    => $tokens,
            'sound' => 'default',
            'data'  => $push->extra,
            'title' => $push->subject,
            'body'  => $push->text
        ];
    }
}
