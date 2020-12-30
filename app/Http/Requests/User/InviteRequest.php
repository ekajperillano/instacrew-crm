<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

use App\Rules\DoesntExistRule;

class InviteRequest extends BaseRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role_id' => 'required|exists:roles,id',
            'name' => 'required',
            'email' => [
                'required',
                'email',
                new DoesntExistRule('users', 'email')
            ]
        ];
    }
}
