<?php

namespace App\Console\Commands;

use App\Models\Date;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class FillDatesTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dates:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill dates table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $period = CarbonPeriod::create(Carbon::now()->toDateString(), Carbon::now()->addYears(2));
        $dates  = [];
        foreach ($period as $date) {
            $dates[] = [
                'date'                  => $date->toDateString(),
                'day_of_week'           => mb_ucfirst($date->getTranslatedDayName()),
                'day_number_in_week'    => $date->dayOfWeek,
            ];
        }

        Date::query()->insert($dates);
    }
}
