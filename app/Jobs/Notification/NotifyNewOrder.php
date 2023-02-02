<?php

namespace App\Jobs\Notification;

use App\Models\NewOrderNumberForNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Notification as EloquentNotification;

class NotifyNewOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $users;
    protected $orderNumbers;
    protected $notification;
    protected $data;
    protected $replace;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($users, $orderNumbers, $notification, $data = [], $replace = [])
    {
        $this->users = $users;
        $this->orderNumbers = $orderNumbers;
        $this->notification = $notification;
        $this->replace = $replace;
        $this->data = $data;
        $this->onQueue('notification');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->notification)) {
            return;
        }

        foreach ($this->users as $user) {
            $this->sendNotification($user);
        }
        $data = [];
        foreach ($this->orderNumbers as $orderNumber) {
            $data[] = [
                'order_number' => $orderNumber,
                'created_at' => now()
            ];
        }
        NewOrderNumberForNotification::insert($data);
    }

    public function sendNotification(User $user)
    {
        return $this->notification->toPush(notifiable: $user)->send();
    }
}
