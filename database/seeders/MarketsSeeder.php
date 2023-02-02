<?php

namespace Database\Seeders;

use App\Models\Market;
use Illuminate\Database\Seeder;

class MarketsSeeder extends Seeder
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
                'id' => 1,
                'name' => 'Europharma',
                'number' => 1,
                'is_active' => 1,

            ],
            [
                'id' => 2,
                'name' => 'Emart',
                'number' => 2,
                'is_active' => 1,

            ],
            [
                'id' => 3,
                'name' => 'ArzanMart',
                'number' => 4,
                'is_active' => 1,

            ],
            [
                'id' => 4,
                'name' => 'Darkstore',
                'number' => 6,
                'is_active' => 1,

            ],
            [
                'id' => 5,
                'name' => 'Fermag',
                'number' => 7,
                'is_active' => 1,

            ],
            [
                'id' => 6,
                'name' => 'Tary',
                'number' => 8,
                'is_active' => 1,

            ]
        ];
        foreach ($data as $datum) {
            Market::insert($datum);
        }
    }
}
