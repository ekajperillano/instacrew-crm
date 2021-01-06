<?php

namespace App\Http\Controllers;

use App\Models\UserEmail;
use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Requests\UserEmail\StoreRequest;
use App\Http\Requests\UserEmail\UpdateRequest;


class UserEmailController extends Controller
{
    public function store(StoreRequest $request) {
        $data = $request->all();

        $email = UserEmail::create(array_merge($data, ['user_id' => \Auth::user()->id]));

        if($email) {
            return response()->json(['status' => 'success', 'data' => $email], config('constants.response_codes.success'));
        }

        return response()->json(['status' => 'error', 'errors' => ['something went wrong']], config('constants.response_codes.error'));
    }

    public function update(UpdateRequest $request, UserEmail $userEmail) {
        $data = $request->all();

        $updated = $userEmail->update($data);

        if($updated) {
            return response()->json(['status' => 'success', 'data' => $updated], config('constants.response_codes.success'));
        }

        return response()->json(['status' => 'error', 'errors' => ['something went wrong']], config('constants.response_codes.error'));
    }

    public function destroy(Request $request, UserEmail $userEmail) {
        $deleted = $userEmail->delete();

        if($deleted) {
            return response()->json(['status' => 'success', 'data' => $deleted], config('constants.response_codes.success'));
        }

        return response()->json(['status' => 'error', 'errors' => ['something went wrong']], config('constants.response_codes.error'));
    }

    public function byUser(Request $request, User $user) {
        
        if($user) {
            $user->load(['emails']);
            return response()->json(['status' => 'success', 'data' => $user->emails], config('constants.response_codes.success'));
        }

        return response()->json(['status' => 'error', 'errors' => ['something went wrong']], config('constants.response_codes.error'));
    }
}
