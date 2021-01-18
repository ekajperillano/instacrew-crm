<?php

namespace App\Http\Controllers;

use App\Models\Social;

use Illuminate\Http\Request;
use App\Http\Requests\Social\IndexRequest;
use App\Http\Requests\Social\StoreRequest;
use App\Http\Requests\Social\UpdateRequest;
use App\Http\Requests\Social\DestroyRequest;

class SocialController extends Controller
{   
    protected $includes = [
        'client'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        $data = $request->all();

        $clients = Social::with($this->includes)->when(isset($data['search']), function($query) use($data) {
            $search = $data['search'];
            return $query->where( function($where) use($search) {
                return $where->where('url', 'LIKE', '%'.$search.'%');
            });

        })->when(isset($data['filters']), function($query) use($data) {
            return $this->applyFilter($query, $data['filters']);
        })->when(isset($data['sort']), function($query) use($data) {
        	$sort = $data['sort'];
            list($field, $order) = explode("|", $sort);
        	return $query->orderBy($field, $order);
        })->paginate(config('constants.pagination_limit'));

        return response()->json($clients);
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
            $created = Social::create($data);
            return response()->json(['status' => 'success', 'data' => $created->load($this->includes)], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Social  $social
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, Social $social)
    {
        try {
            $record = $social->load($this->includes);
            return response()->json(['status' => 'success', 'data' => $record], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Social  $social
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Social $social)
    {
        try {
            $data = $request->all();
            $updated = $social->update($data);
            return response()->json(['status' => 'success', 'data' => $updated], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Social  $social
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, Social $social)
    {
        try {
            $deleted = $social->delete();
            return response()->json(['status' => 'success', 'data' => $deleted], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }
}
