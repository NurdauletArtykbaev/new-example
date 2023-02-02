<?php

namespace App\Services\Push\Classes;

use App\Helpers\DeviceTokenHelper;
use App\Helpers\NotificationHelper;
use App\Repositories\DeviceTokenRepository;
use App\Services\Push\Jobs\SendPushNotificationJob;

class PushNotification
{
    private $pushable;
    private mixed $receiver;
    private array $push;
    private $text;

    public function __construct($pushable, $notifiable = null, $data = [], $replace = [])
    {
        $this->pushable = $pushable;
        $this->receiver = $notifiable;
        $this->text = $this->applyReplacement($data['text'] ?? $this->pushable?->text, $replace);
        $this->push = $this->prepare($data, $replace);
    }

    private function prepare($data, $replace = [])
    {
        $body = [
            'subject' => $this->applyReplacement($this->pushable->subject ?? 'Eclub', $replace),
            'text' => $this->applyReplacement($data['text'] ?? $this->pushable?->text, $replace),
            'extra' => [
                'badge' => $this->receiver?->unreadSentPushNotifications()->count(),
                'image' => $this->pushable?->firstImgSrc
            ]
        ];

        return $body;
    }

    public function send()
    {
        $test = match (config('services.push.connection')) {
            'sync' => \Push::notify($this),
            'queue' => SendPushNotificationJob::dispatch($this)->onQueue('notification'),
        };
        return $test;
    }

    public function getReceiver()
    {
        return $this->receiver;
    }

    public function __get($attribute)
    {
        return $this->push[$attribute] ?? null;
    }

    public function handleResponse($responseData)
    {
        [$response, $driver] = $responseData;
        $status = NotificationHelper::STATUS_UNREAD;
        if ($driver == DeviceTokenHelper::DRIVER_EXPO) {

            if (empty($response) || !isset($response['data']) || !isset($response['data'][0])) {
                (new DeviceTokenRepository())->deleteUserTokens($this->receiver, $driver);
                return false;
            }

            $status = $response['data'][0]['status'] ?? null;
            if ($status && $status === 'ok') {
                $status = NotificationHelper::STATUS_UNREAD;
            } else {
                (new DeviceTokenRepository())->deleteUserTokens($this->receiver, $driver);
                $status = NotificationHelper::STATUS_FAILED;
            }
        }

        $data = [
            'status' => $status,
            'user_id' => $this->receiver->id,
            'fields_json' => $response['data'] ?? ($response['data'][0] ?? []),
        ];

        if ($this->text) {
            $data['fields_json']['text'] = $this->text;
        }
        if ($this->push['subject']) {
            $data['fields_json']['subject'] = $this->push['subject'];
        }

        if ($status == NotificationHelper::STATUS_UNREAD) {
            $data['token_id'] = $response['data'] ?? ($response['data'][0]['id'] ?? null);
        }
        $this->pushable->sentPushNotifications()->create($data);
        return $status && $status === 'ok';
    }

    private function applyReplacement(string $str, array $replace = []): string
    {
        return str_replace(
            array_map(function ($key) {
                return "{{$key}}";
            }, array_keys($replace)),
            array_values($replace),
            $str
        );
    }
}
