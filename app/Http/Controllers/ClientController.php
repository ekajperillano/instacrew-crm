<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Client\IndexRequest;
use App\Http\Requests\Client\ShowRequest;
use App\Http\Requests\Client\StoreRequest;
use App\Http\Requests\Client\UpdateRequest;
use App\Http\Requests\Client\SetAsCrewRequest;
use App\Http\Requests\Client\InactiveRequest;
use App\Http\Requests\Client\ActiveRequest;

use App\Models\Client;

use App\Traits\FilterTrait;
class ClientController extends Controller
{   
    use FilterTrait;

    protected $includes = [
        'socials'
    ];

    public function index(IndexRequest $request) {
        $data = $request->all();

        $clients = Client::with($this->includes)->when(isset($data['search']), function($query) use($data) {
            $search = $data['search'];
            return $query->where( function($where) use($search) {
                return $where->where('name', 'LIKE', '%'.$search.'%');
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

    public function store(StoreRequest $request) {
        $data = $request->all();

        try {
            $created = Client::create($data);
            return response()->json(['status' => 'success', 'data' => $created->load($this->includes)], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }

    public function update(UpdateRequest $request, Client $client) {
        try {
            $data = $request->all();
            $updated = $client->update($data);
            return response()->json(['status' => 'success', 'data' => $updated], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }

    public function inactive(Request $request, Client $client) {
        try {
            $client->active = false;
            $client->save();
            $record = $client->load($this->includes);

            return response()->json(['status' => 'success', 'data' => $record], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }

    public function active(Request $request, Client $client) {
        try {
            $client->active = true;
            $client->save();
            $record = $client->load($this->includes);

            return response()->json(['status' => 'success', 'data' => $record], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }

    public function setAsCrew(SetAsCrewRequest $request, Client $client) {
        try {
            if($client->type == config('constants.client_type.crew')) {
                return response()->json(
                    [
                        'status' => 'error',
                        'errors' => ['Cannot update client. Client already part of the crew.']
                    ], config('constants.response_codes.error'));
            }

            $client->type = config('constants.client_type.crew');
            $client->save();
            $record = $client->load($this->includes);

            return response()->json(['status' => 'success', 'data' => $record], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }

    public function show(ShowRequest $request, Client $client) {
        try {
            $record = $client->load($this->includes);
            return response()->json(['status' => 'success', 'data' => $record], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }
}
