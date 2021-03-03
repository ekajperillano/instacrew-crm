<?php
namespace App\Services;

use Illuminate\Notifications\Notification;

use Auth;

use App\Models\User;
use App\Models\NotificationSubscriber;

class NotificationService {

    private $notification;
    private $user;

    public function __construct(Notification $notification) {
        $this->notification = $notification;
    }

    public function notify(User $user = null) {

        $subscribers = NotificationSubscriber::with('user')
            ->where('type', get_class($this->notification))
            ->when(!is_null($user), function($query) use($user) {
                $query->whereUserId($user->id);
            })->get();
        
        if(!is_null($subscribers)) {
            foreach ($subscribers as $subscriber) {
                $subscriber->user->notify($this->notification);
            }
        }
    }
}

?>
