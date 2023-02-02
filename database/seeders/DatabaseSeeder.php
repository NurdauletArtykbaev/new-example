<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (! User::query()->first()) {
            User::query()->create(['email' => 'admin@admin.com', 'password' => 'password']);
            $store = Store::query()->create(['name' => 'Emart 1', 'number' => 2001]);
            $user = User::query()->create([
                'personal_number' => 123456,
                'password'  => 'password',
                'first_name' => 'Zhandos',
                'last_name' => 'Abylai',
            ]);
            $user->store()->sync([$store->id]);
        }
        $this->call(NotificationsSeeder::class);
    }
}
