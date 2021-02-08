<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Client;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {   

        return $this->user()->can('view_client_list', Client::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
