<?php

namespace App\Observers;

use App\Helpers\Status;
use App\Models\UserOrder;

class UserOrderObserver
{
    public function creating(UserOrder $userOrder) {
        $userOrder->priority = Status::getPriority($userOrder->status);
    }

    public function updating(UserOrder $userOrder) {
        if ($userOrder->isDirty('status')) {
            $userOrder->priority = Status::getPriority($userOrder->status);
        }
    }
}
