<?php

namespace App\Services\Push\Jobs;

use App\Services\Push\Classes\PushNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPushNotificationJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $push;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PushNotification $push)
    {
        $this->push = $push;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Push::notify($this->push);
    }
}
