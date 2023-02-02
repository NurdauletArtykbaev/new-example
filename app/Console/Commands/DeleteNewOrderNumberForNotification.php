<?php

namespace App\Console\Commands;

use App\Models\NewOrderNumberForNotification;
use Illuminate\Console\Command;

class DeleteNewOrderNumberForNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:new-order-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete new order notifications';

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
        NewOrderNumberForNotification::where('created_at', '<=',now()->subDay())->delete();
        return 0;
    }
}
