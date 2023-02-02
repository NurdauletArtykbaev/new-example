<?php

namespace App\Helpers;

class Status
{
    const PROCESSING        = 'processing';
    const FINISHED          = 'finished';
    const CANCELED          = 'canceled';
    const ON_DELIVERY       = 'on_delivery';
    const DELIVERED         = 'delivered';
    const PENDING_APPROVAL  = 'pending_approval';
    const NEW               = 'new';

    const PRIORITIES = [
        self::NEW               => 4,
        self::PROCESSING        => 3,
        self::FINISHED          => 2,
        self::CANCELED          => 2,
        self::ON_DELIVERY       => 2,
        self::DELIVERED         => 2,
        self::PENDING_APPROVAL  => 2,
    ];

    public static function getPriority($value) {
        return self::PRIORITIES[$value];
    }
}
