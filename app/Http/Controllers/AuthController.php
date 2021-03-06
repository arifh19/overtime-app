<?php

namespace App\Http\Controllers;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Hash;
use JWTAuth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
            'username' => 'required',
            'password' => 'required',
            'departemen_id' => 'required',
            'role_id' => 'required',
        ]);
      
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $credentials = $request->only('name', 'username', 'password','role_id','departemen_id');
        $name = $request->name;
        $username = $request->username;
        $password = $request->password;
        $departemen_id = $request->departemen_id;
        $user = User::create(['name' => $name, 'username' => $username, 'password' => Hash::make($password), 'departemen_id' => $departemen_id]);
        $role = Role::where('id', $request->role_id)->first();
        $user->attachRole($role);

        return response()->json(['success'=> true, 'message'=> 'Thanks for signing up!']);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');        

        try {
            if (! $token = auth()->setTTL(9999999)->attempt($credentials)) {
                return response()->json(['success' => false, 'error' => 'We cant find an account with this credentials. Please make sure you entered the right information.'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'error' => 'Failed to login, please try again.'], 500);
        }
        return response()->json(['success' => true, 'token' => $token,'expires_in' => auth()->factory()->getTTL() * 60,'role' => auth()->user()->roleUser->role->name]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
