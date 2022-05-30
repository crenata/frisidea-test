<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use JWTAuth;
use Validator;
use App\Models\User;

class UserAuthController extends Controller
{
    // create new user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'nim' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:table_user',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());       
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'nim' => $request->nim,
            'email' => $request->email,
            'password' => Hash::make($request->password)
         ]);

        return response()->json(['data' => $user, 'message' => 'success']);
    }

    // login user akun
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->guard('user_api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    // get self data
    public function me()
    {
        return response()->json(auth('user_api')->user());
    }

    // logout
    public function logout()
    {
        auth('user_api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    // refresh access token
    public function refresh()
    {
        return $this->respondWithToken(auth('user_api')->refresh());
    }

    // func helper
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }
}
