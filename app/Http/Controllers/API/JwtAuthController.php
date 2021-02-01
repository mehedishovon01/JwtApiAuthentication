<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class JwtAuthController extends Controller
{
    // Auth Guard
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'registration']]);
    }

    // Registration
    public function registration(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:2,100',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|confirmed|min:4',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'message' => 'User Registered Successfully !',
            'user' => $user
        ], 201);
    }
    
    // Login
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $token = Auth::attempt($validator->validated());
        if (! $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    // Logout
    public function logout() {
        Auth::logout();

        return response()->json(['message' => 'User Signed Out Successfully !']);
    }

    // User Profile 
    public function userProfile() {
        return response()->json(auth()->user());
    }

    // Token
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => Auth::user()
        ]);
    }
}
