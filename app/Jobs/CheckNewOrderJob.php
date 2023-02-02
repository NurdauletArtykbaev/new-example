<?php

namespace App\Jobs;

use App\Models\Market;
use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckNewOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('order');

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stores = Store::query()->select('id', 'number')->get();

        foreach ($stores as $store) {
            dispatch(new CheckNewOrderByStoreJob($store->number))->onQueue('order');
        }
    }
}
