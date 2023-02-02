<?php

namespace App\Console\Commands;

use App\Models\Shift;
use App\Models\Store;
use App\Models\User;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ParseWorkersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:workers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse workers from Excel';

    const FIRST_NAME    = 0;
    const LAST_NAME     = 1;
    const STORE_NUMBER  = 2;
    const PERS_NUMBER   = 3;
    const SHIFT         = 4;
    const START_AT      = 5;
    const HOURS         = 6;
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file = __DIR__ . "/../../../storage/schedules.xlsx";
        $doc  = Excel::toArray(null, $file);
        $rows = array_slice($doc[0], 1);
        $stores = array_filter(array_unique(array_column($rows, self::STORE_NUMBER)));
        $storesArray = [];

        foreach ($stores as $store) {
            $store = explode(' ', trim($store));
            $number = (int) $store[0];
            $storesArray[$number] = Store::query()->updateOrCreate(['number' => $number], [
                'name'      => $store[1],
                'address'   => $store[1],
            ]);
        }

        foreach ($rows as $row) {
            if (empty($row[self::PERS_NUMBER])) {
                continue;
            }
            $user = User::query()->updateOrCreate(['personal_number' => trim($row[self::PERS_NUMBER])], [
                'first_name' => trim($row[self::FIRST_NAME]),
                'last_name'  => trim($row[self::LAST_NAME]),
                'password'   => 'password'
            ]);
            $storeNumber = (int) preg_replace('/[^0-9]/', '', $row[self::STORE_NUMBER]);
            $user->store()->sync([$storesArray[$storeNumber]->id]);

            if (! $user->shift) {
                $userShift  = explode('\\', trim($row[self::SHIFT]));
                $hours      = (int) preg_replace('/[^0-9]/', '', $row[self::HOURS]);
                $shift      = Shift::query()->updateOrCreate([
                    'days_work' => $userShift[0],
                    'days_rest' => $userShift[1],
                    'hours'     => $hours,
                    'start_at'  => $row[self::START_AT]
                ], [
                    'name' => "Смена ${userShift[0]}\\${userShift[1]} (Начало раб.дня -> ${row[self::START_AT]}. На ${hours}ч)."
                ]);
                $user->shifts()->attach($shift);
            }
        }

        $this->info('Successfully parsed employees.');
    }
}
