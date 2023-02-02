<?php

namespace App\Services\Push;

use App\Services\Push\Classes\PushNotification;
use App\Services\Push\Contracts\PushDriverInterface;

class PushService
{
    public function __construct(private PushDriverInterface $driver)
    {
    }

    public function notify(PushNotification $push)
    {
        $response = $this->driver->notify($push);

        return $push->handleResponse($response);
    }

    public function __call($method, array $parameters = [])
    {
        return $this->driver->{$method}(...$parameters);
    }
}
