<?php

namespace App\Http\Controllers\Foreign;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Models\Client;

class ClientController extends Controller
{
    
    public function store(Request $request)
    {   
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email'],
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),  'code' =>  config('constants.response_codes.error')], config('constants.response_codes.error'));
        }


        $client = Client::create([
            'name' => $data['name'],
            'created_by' => 'website',
            'type' => config('constants.client_type.prospect')
        ]);
        
        if (!is_null($client)) {
            $client->contacts()->create([
                'key' => 'email',
                'label' => 'Email',
                'value' => $data['email']
            ]);
        }
    }

}
