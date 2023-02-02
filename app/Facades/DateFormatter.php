<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DateFormatter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'date_formatter';
    }
}
