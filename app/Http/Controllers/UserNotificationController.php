<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

use App\Http\Requests\Notification\UserNotificationRequest;
use Auth;

class UserNotificationController extends Controller
{
    public function unreadCount(Request $request, User $user) {

        $data  = $request->all();

        if(!isset($data['since_created_at'])) {
            $notifications = $user->unreadNotifications;
        } else {
            $notifications = $user->unreadNotifications()->where('created_at', '>', $data['since_created_at'])->get();
        }

        return response()->json(['status' => 'success', 'data' => $notifications], config('constants.response_codes.success'));
    }

    public function notifications(UserNotificationRequest $request, User $user) {
        $data  = $request->all();
        if(!isset($data['since_id'])) {
            $notifications = $user->unreadNotifications;
        } else {
            $notifications = $user->unreadNotifications()->skip($data['since_id'])->take($limit)->get();
        }

        return response()->json(['status' => 'success', 'data' => $notifications], config('constants.response_codes.success'));
    }

    public function read(UserNotificationRequest $request, User $user) {

        $id = $request->get('notification_id');
        $notification = DatabaseNotification::find($id);

        if(!is_null($notification)) {
            $notification->markAsRead();
            return response()->json(['status' => 'success', 'data' => true], config('constants.response_codes.success'));
        }

        return response()->json(['status' => 'success', 'data' => 'no record'], config('constants.response_codes.success'));

    }

    public function readAll(Request $request) {

        $user = Auth::user();

        if(!is_null($user)) {
            $user->unreadNotifications->markAsRead();
            return response()->json(['status' => 'success', 'data' => true], config('constants.response_codes.success'));
        }

        return response()->json(['status' => 'success', 'data' => 'no records'], config('constants.response_codes.success'));
    }
}