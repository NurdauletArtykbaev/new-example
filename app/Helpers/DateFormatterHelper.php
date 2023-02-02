<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateFormatterHelper
{
    public function secondsToHumanReadable($seconds) {
        $diff = Carbon::now()->diff(Carbon::now()->addSeconds($seconds));
        $result = $diff->h > 0 ? "{$diff->h} ч {$diff->i} м" : "{$diff->i} м";

        return $result;
    }
}
