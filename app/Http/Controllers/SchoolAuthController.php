<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use JWTAuth;
use Validator;
use App\Models\School;

class SchoolAuthController extends Controller
{
    // create new user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'fax' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'website' => 'required|string|max:255',
            'logo' => 'required|string|max:255',
            'about' => 'required|string|max:255',
            'mission' => 'required|string|max:255',
            'vision' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:table_school',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());       
        }

        $user = School::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'fax' => $request->fax,
            'address' => $request->address,
            'website' => $request->website,
            'logo' => $request->logo,
            'about' => $request->about,
            'mission' => $request->mission,
            'vision' => $request->vision,
            'postal_code' => $request->postal_code,
            'email' => $request->email,
            'password' => Hash::make($request->password)
         ]);

        return response()->json(['data' => $user, 'message' => 'success']);
    }

    // login school akun
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->guard('school_api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    // get self data
    public function me()
    {
        return response()->json(auth('school_api')->user());
    }

    // logout
    public function logout()
    {
        auth('school_api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    // refresh access token
    public function refresh()
    {
        return $this->respondWithToken(auth('school_api')->refresh());
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
