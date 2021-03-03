<?php

namespace App\Console\Commands\Notification;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\NotificationSubscribers;

class SubscribeTo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:subscribe {--class=} {--email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $class = $this->option('class');
        $email = $this->option('email');

        if (!$class || !$email) {
            die("Fill all fields\n");
        }

        $user = User::with('notification_subscriptions')->whereEmail($email)->first();

        if(is_null($user)) {
            die("user not existing\n");
        }

        if(!is_null($user)) {
            $notificationClass = "App\\Notifications\\" . $class;

            if(class_exists($notificationClass)){
                $created  = $user->notification_subscriptions()->firstOrCreate([
                    'type' => $notificationClass
                ]);

                if(!is_null($created)) {
                    echo "User successfuly subscribed to ".$class."\n";
                }

            } else {
                die("class is not existing\n");
            }
        }


    }
}
