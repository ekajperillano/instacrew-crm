<?php

namespace App\Http\Requests\ClientNote;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'note' => 'required',
            'client_id' => 'required|exists:clients,id',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);

        $data['user_id'] = \Auth::user()->id;

        return $data;
    }
}
