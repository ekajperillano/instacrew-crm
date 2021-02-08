<?php

namespace App\Http\Requests\ClientContact;

use App\Http\Requests\BaseRequest;

use App\Rules\ClientContact\ValidKeyValueContactRule;

class StoreRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create_contact', App\Models\ClientContact::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'key' => [
                'required',
                new ValidKeyValueContactRule($this->all())
            ],
            'value' => [
                'required'
            ],
            'label' => [
                'required'
            ],
            'client_id' => [
                'required',
                'exists:clients,id'
            ],
        ];
    }
}
