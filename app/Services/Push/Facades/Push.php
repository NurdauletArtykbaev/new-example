<?php

namespace App\Services\Push\Facades;

use Illuminate\Support\Facades\Facade;

class Push extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pushService';
    }
}
