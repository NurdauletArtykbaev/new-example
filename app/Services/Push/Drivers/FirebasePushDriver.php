<?php

namespace App\Services\Push\Drivers;

use App\Helpers\DeviceTokenHelper;
use App\Services\Push\Classes\PushNotification;
use App\Services\Push\Contracts\PushDriverInterface;
use Illuminate\Support\Facades\Http;

class FirebasePushDriver implements PushDriverInterface
{
    const URL = 'https://fcm.googleapis.com/fcm/send';

    public function notify(PushNotification $push)
    {
        $data = $this->prepare($push);

        $response  = Http::withHeaders([
            'Authorization' => 'key=' .env('FCM_SERVER_KEY'),
            'Content-Type'  => 'application/json',
        ])->post(self::URL, $data)->json();

        return [ $response,DeviceTokenHelper::DRIVER_FIREBASE];
    }

    public function prepare(PushNotification $push) {
        $tokens = $push->getReceiver()
            ->deviceTokens()
            ->where('driver', DeviceTokenHelper::DRIVER_FIREBASE)
            ->get();
        $tokens = array_merge($tokens->pluck('value')->whereNotNull()->toArray(), $tokens->pluck('installation_id')->whereNotNull()->toArray());

        return [
            "registration_ids" => $tokens,
            "notification" => [
                "title" => $push->subject,
                "body"  => $push->text,
                "sound" => "default"
            ]
        ];
    }
}
