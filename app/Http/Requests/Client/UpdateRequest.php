<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
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
            'name' => 'required|min:5',
            'address' => 'string|nullable',
            'type' => 'in:crew,prospect'
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);

        if(!isset($data['type'])) {
            $data['type'] = config('constants.client_type.prospect');
        }

        return $data;
    }
}
