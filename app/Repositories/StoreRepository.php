<?php

namespace App\Repositories;

use App\Models\Store;

class StoreRepository
{
    public function getByNumber($number) {
        return Store::query()->where('number', $number)->first();
    }
}
