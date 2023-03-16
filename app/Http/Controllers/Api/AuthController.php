<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    use ApiResponse;

    public function createUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return $this->sendError($validateUser->errors(), 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return $this->sendSuccess([
                'token' => $user->createToken("auth_token")->plainTextToken
            ], 'Success to create user');

        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return $this->sendError($validateUser->errors(), 400);
            }

            
            if(!Auth::attempt($request->only(['email', 'password']))){
                return $this->sendError('Wrong Username or Password', 401);
            }

            $user = User::where('email', $request->email)->first();

            return $this->sendSuccess([
                'token' => $user->createToken("auth_token")->plainTextToken
            ], 'Success to Login');

        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 500);

        }
    }
}
