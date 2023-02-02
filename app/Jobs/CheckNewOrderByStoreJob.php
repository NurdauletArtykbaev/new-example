<?php

namespace App\Jobs;

use App\Helpers\NotificationHelper;
use App\Jobs\Notification\NotifyNewOrder;
use App\Models\NewOrderNumberForNotification;
use App\Models\User;
use App\Repositories\CrmApiRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckNewOrderByStoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private int $storeNumber)
    {
        $this->onQueue('notification');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $orders = collect((new CrmApiRepository())->getOrdersByStoreNumber($this->storeNumber));

        $users = User::whereHas('store', fn($query) => $query->where('number', $this->storeNumber))->get();

        if ($users->isEmpty()) {
            return;
        }

        $orders = $this->diffOrdersInNewFixedOrderNumbers($orders);
        if ($orders->isEmpty()) {
            return;
        }

        $notification = \App\Models\Notification::where('key',NotificationHelper::KEY_NEW_ORDER)->first();
        NotifyNewOrder::dispatch($users,$orders->pluck('number')->toArray(), $notification, [], [] )->onQueue('notification');
    }

    private function diffOrdersInNewFixedOrderNumbers($orders)
    {
        $newOrderFixedNumbers = NewOrderNumberForNotification::whereIn('order_number', $orders->pluck('number')->toArray())
            ->select('order_number')
            ->get()
            ->pluck('order_number')
            ->toArray();

        $newOrderFixedNumbers = array_diff($orders->pluck('number')->toArray(), $newOrderFixedNumbers);
        return $orders->whereIn('number', $newOrderFixedNumbers);
    }
}
