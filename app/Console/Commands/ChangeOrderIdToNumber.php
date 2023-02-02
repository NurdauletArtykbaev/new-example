<?php

namespace App\Console\Commands;

use App\Models\UserOrder;
use Illuminate\Console\Command;

class ChangeOrderIdToNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:to-number';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change order id to order number';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (UserOrder::query()->get() as $userOrder) {
            $userOrder->order_id = $userOrder->order_data['number'];
            $userOrder->saveOrFail();
        }
    }
}
