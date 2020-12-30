<?php
namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Requests\BaseRequest;
use App\Http\Requests\User\InviteRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\User\PasswordUpdateRequest;

use Mail;
use App\Mail\User\ResetPassword;
use App\Mail\User\InviteUser;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(BaseRequest $request) {
        $data = $request->all();

        $users = User::with(['role'])->when(isset($data['search']), function($query) use($data) {
            $search = $data['search'];

            return $query->where( function($where) use($search) {
                return $where->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('email', 'LIKE', '%'.$search.'%');
            });

        })->when(isset($data['sort']), function($query) use($data) {

        	$sort = $data['sort'];
        	list($field, $order) = explode("|", $sort);

        	return $query->orderBy($field, $order);

        })->paginate(config('constants.pagination_limit'));

        return response()->json($users);

    }

    public function show(Request $request, $id)
    {
        $user = User::find($id);
        return response()->json(
            [
                'status' => 'success',
                'user' => $user->toArray()
            ], 200);
    }

    public function invite(InviteRequest $request) {
        $data = $request->all();
        
        $token = sha1(uniqid($data['email'] . '|' . Carbon::now() . '|' . $data['name'], true));

        $user = User::create([
            'email' => $data['email'],
            'name' => $data['name'],
            'invite_token' => $token,
            'role_id' => $data['role_id'],
            'password' => Hash::make($token . '|' . $data['email']),
            'created_invite_token_at' => Carbon::now()->format(config('constants.date_time_format'))
        ]);

        if(!is_null($user)) {
            Mail::to($user->email)->send(new InviteUser($user, $token));

            return response()->json([
                'status' => 'success',
                'data' => true
            ], config('constants.response_codes.success'));
        }

        return response()->json(['status' => 'error', 'errors' => ['something went wrong']], config('constants.response_codes.error'));
    }

    public function update(UpdateRequest $request, User $user) {

        $data = $request->all();

        try {
            $updated = $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'active' => $data['active'],
                'role_id' => $data['role_id'],
            ]);

            return response()->json(['status' => 'success', 'data' => $updated], config('constants.response_codes.success'));
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'errors' => ['something went wrong'], 'exception' => $ex->getMessage()], config('constants.response_codes.error'));
        }
    }

    public function resetPasswordRequest(ResetPasswordRequest $request) {

        $data = $request->all();
        $email = $data['email'];

        $user = User::whereEmail($email)->first();

        $token = sha1(uniqid($email . '|' . Carbon::now() . '|' . $user->name, true));

        PasswordReset::create([
            'token' => $token,
            'email' => $user->email,
            'created_at' => Carbon::now()
        ]);

        //Mail password reset form
        Mail::to($email)->send(new ResetPassword($user, $token));

        return response()->json([
            'status' => 'success',
            'token' => $token
        ], config('constants.response_codes.success'));
    }

    public function passwordUpdate(PasswordUpdateRequest $request, User $user) {
        $data = $request->all();
        
        $user->password = Hash::make($data['password']);
        $user->save();

        return response()->json([
            'status' => 'success',
            'data' => true
        ], config('constants.response_codes.success'));

    }
}