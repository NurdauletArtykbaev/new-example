<?php

namespace App\Services\Push\Contracts;

use App\Services\Push\Classes\PushNotification;

interface PushDriverInterface
{
    public function notify(PushNotification $push);
}
