<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Permission;

class PermissionController extends Controller
{
    public function list(Request $request) {
        $permissions = Permission::orderBy('order_code')->get();

        return response()->json(['status' => 'success', 'data' => $permissions], config('constants.response_codes.success'));
    }
}
