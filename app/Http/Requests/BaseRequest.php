<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Auth;

class BaseRequest extends FormRequest
{
    public $rules = [];
    public $mode = 'store';
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
    public function rules() {
        return $this->rules;
    }

    public function all($keys = null) {
        $data = parent::all($keys);

        $mode = $this->segment(3);
        $method = $this->method();
        $user = Auth::user();
        
        if($mode == 'export') {
            $data['fields'] = json_decode($data['fields']);
        }

        unset($data['q']);

        $advanceFilters = array();
        if(isset($data['filters'])) {
            $advanceFilters = json_decode($data['filters']);
        }

        unset($data['filters']);

        foreach($advanceFilters as $field => $value) {
            $data['filters'][$field] = $value;
        }   

        if($user) {
            if($method == 'PATCH') {
                $data['updated_by'] = $user->id;
            } else if ($method == 'POST') {
                $data['created_by'] = $user->id;
            }
        }

        return $data;
    }
}
