<?php

namespace Database\Seeders;

use App\Helpers\NotificationHelper;
use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'key' => NotificationHelper::KEY_NEW_ORDER,
                'description' => 'Новый заказ',
                'subject' => 'У вас новый заказ',
                'text' => 'У вас новый заказ',
                'send_sms' => false,
            ]
        ];
        foreach ($data as $datum) {
            Notification::create($datum);
        }
    }
}
