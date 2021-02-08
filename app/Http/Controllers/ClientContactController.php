<?php

namespace App\Http\Controllers;

use App\Models\ClientContact;

use Illuminate\Http\Request;
use App\Http\Requests\ClientContact\IndexRequest;
use App\Http\Requests\ClientContact\StoreRequest;
use App\Http\Requests\ClientContact\UpdateRequest;
use App\Http\Requests\ClientContact\DestroyRequest;

class ClientContactController extends Controller
{
    protected $includes = [
        'client'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $request->all();

        $contacts = ClientContact::with($this->includes)->when(isset($data['search']), function($query) use($data) {
            $search = $data['search'];
            return $query->where( function($where) use($search) {
                return $where->where('contact', 'LIKE', '%'.$search.'%');
            });

        })->when(isset($data['filters']), function($query) use($data) {
            return $this->applyFilter($query, $data['filters']);
        })->when(isset($data['sort']), function($query) use($data) {
        	$sort = $data['sort'];
            list($field, $order) = explode("|", $sort);
        	return $query->orderBy($field, $order);
        })->paginate(config('constants.pagination_limit'));

        return response()->json($contacts);
    }

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
            $created = ClientContact::create($data);
            return response()->json(['status' => 'success', 'data' => $created->load($this->includes)], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClientContact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, ClientContact $contact)
    {
        try {
            $record = $contact->load($this->includes);
            return response()->json(['status' => 'success', 'data' => $record], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClientContact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, ClientContact $contact)
    {
        try {
            $data = $request->all();
            $updated = $contact->update($data);
            return response()->json(['status' => 'success', 'data' => $updated], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClientContact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, ClientContact $contact)
    {
        try {
            $deleted = $contact->delete();
            return response()->json(['status' => 'success', 'data' => $deleted], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }
}
