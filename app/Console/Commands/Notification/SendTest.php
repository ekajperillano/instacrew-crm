<?php

namespace App\Console\Commands\Notification;

use Illuminate\Console\Command;

use App\Services\NotificationService;
use App\Notifications\TestNotification;

class SendTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send test notification';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notification  = new NotificationService(new TestNotification());
        $notification->notify();
    }
}
