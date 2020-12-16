<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Role\IndexRequest;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;

use App\Models\Role;

class RoleController extends Controller
{
    public function options(IndexRequest $request) {
        $data = $request->all();

        $tags = Role::with(['permissions'])->when(isset($data['search']), function($query) use($data) {
            $search = $data['search'];
            return $query->where('name', 'LIKE', '%'.$search.'%');
        })->limit(config('constants.pagination_limit'))->get();

        return response()->json($tags);
    }

    public function store(StoreRequest $request) {
        $data = $request->all();

        try {
            $created = Role::create([
                'name' => $data['name']
            ]);
            
            $created->permissions()->sync($data['permissions']);

            return response()->json(['status' => 'success', 'data' => $created], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }

    public function update(UpdateRequest $request, Role $role) {
        $data = $request->all();
        
        try {
            $role->permissions()->sync($data['permissions']);

            return response()->json(['status' => 'success', 'data' => $role], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }
}
