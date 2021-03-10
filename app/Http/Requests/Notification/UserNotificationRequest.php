<?php

namespace App\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

class UserNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules() {
        $method = $this->method();
        $rules = [];

        switch ($method) {
            case 'PATCH':
            case 'POST':

                $rules = [
                    'notification_id' => 'required|exists:notifications,id'
                ];
                break;
            default:
                # code...
                break;
        }

        return $rules;
    }
}
