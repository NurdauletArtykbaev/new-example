<?php

namespace App\Helpers;

class AdminMenuGenerator
{
    public static function items() {
        return [
            [
                'route' => backpack_url('dashboard'),
                'icon' => 'la la-home',
                'label' => trans('backpack::base.dashboard')
            ],
            [
                'route' => backpack_url('user'),
                'icon' => 'la la-user-lock',
                'label' => trans('admin.user.plural'),
            ],
            [
                'route' => backpack_url('store'),
                'icon' => 'la la-store',
                'label' => trans('admin.store.plural'),
            ],
//            [
//                'route' => backpack_url('market'),
//                'icon' => 'la la-store',
//                'label' => trans('admin.market.plural'),
//            ],
            [
                'route' => backpack_url('shift'),
                'icon' => 'la la-clock',
                'label' => trans('admin.shift.plural'),
            ],
            [
                'route' => backpack_url('notification'),
                'icon' => 'la la la-bell',
                'label' => trans('admin.notification.plural'),
            ],
        ];
    }
}
