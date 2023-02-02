<?php

namespace App\Http\Controllers\Admin;

class NotificationCrudController extends BaseCrudController
{
    protected bool $hasReorderOperation = true;

    protected function setupCreateOperation()
    {
        parent::setupCreateOperation();
    }
}
