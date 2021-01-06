<?php

namespace App\Http\Requests\UserEmail;

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
            'imap_host' => 'required',
            'imap_port' => 'required',
            'smtp_port' => 'required',
            'smtp_host' => 'required',
            'address' => 'required|email',
            'password' => 'required',
        ];
    }
}
