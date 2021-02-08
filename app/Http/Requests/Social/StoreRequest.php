<?php

namespace App\Http\Requests\Social;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\Social\UrlAliveRule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create_social', App\Models\Social::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|in:facebook,instagram,twitter',
            'url' => [
                'required',
                'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
                new UrlAliveRule(),
                'unique:socials'
            ]
        ];
    }
}
