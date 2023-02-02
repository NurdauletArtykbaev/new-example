<?php

namespace App\Exceptions;

interface AssemblerExceptionInterface
{
    const HAS_ACTIVE_ORDER = 1;
    const OFFLINE = 2;
    const ORDER_NOT_COMPLETED = 3;
    const NO_ONLINE_USERS = 4;
}
