<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['register', 'login']]);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'first_name' => 'required',
            'last_name' => 'required'
        ]);
  
        if($validator->fails()) {
            return $this->sendUnprocessedEntity($validator->errors());
        }
  
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
  
        return $this->sendCreated($user);
    }
  
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
  
        if($validator->fails()) {
            return $this->sendUnprocessedEntity($validator->errors());
        }
  
        $credentials =  $request->only(['email', 'password']);
  
        if (!$token = auth()->setTTL(7200)->attempt($credentials)) {
            return $this->sendUnauthorized('Email or Password Wrong!');
        }

        return $this->sendToken($token, auth()->factory()->getTTL() * 60);
    }

    public function me() {
        $user = auth()->user();
        if (!$user) {
            return $this->sendUnauthorized();
        }
        return response()->json($user);
    }

    public function logout() {
        $user = auth()->user();
        if (!$user) {
            return $this->sendUnauthorized();
        } else {
            auth()->logout();
            return response()->json(['message' => 'Successfully logged out']);
        }
    }

    public function refresh() {
        $user = auth()->user();
        if (!$user) {
            return $this->sendUnauthorized();
        }
        return $this->sendToken(auth()->refresh(), auth()->factory()->getTTL() * 60);
    }
}
