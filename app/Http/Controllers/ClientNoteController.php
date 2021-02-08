<?php

namespace App\Http\Controllers;

use App\Models\ClientNote;
use Illuminate\Http\Request;

use App\Http\Requests\ClientNote\IndexRequest;
use App\Http\Requests\ClientNote\StoreRequest;

class ClientNoteController extends Controller
{   
    protected $includes = [
        'client', 'user'
    ];

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();

        try {
            $created = ClientNote::create($data);
            return response()->json(['status' => 'success', 'data' => $created->load($this->includes)], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }
}
