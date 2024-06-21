<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        try {
            $userData = $request->validate([
                'name' => 'required|min:3|max:70',
                'email' => 'required|string|unique:users',
                'phone' => 'required',
                'password' => 'required|min:3',
                'address' => 'min:3|max:255'
            ]);

            // manipulasi object di php
            $userData['password'] = Hash::make($request->input('password'));

            $user = User::create($userData);

            return $this->sendRes([
                'message' => 'success create new account, please login first.'
            ]);

        } catch (\Exception $e) {
            return $this->sendFailRes($e);
        }
    }

    public function login(Request $request) {
        try {
            //validasi form/input
            $request->validate([
                'email' => 'required|min:3|max:40|string',
                'password' => 'required|min:3|string'
            ]);

            if(! Auth::attempt($request->only('email', 'password'))) {
                // return response()->json([
                //     'message' => 'Unauthorized'
                // ], 401);
                throw new Exception('Unauthorized', 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->sendRes([
                'message' => 'Login Success',
                'token' => $token,
                'token_type' => 'Bearer'
            ]);
        } catch (\Exception $e) {
            return $this->sendFailRes($e);
        }
    }

    public function logout(Request $request) {
        try {
            $request->user()->tokens()->delete();

            return $this->sendRes([
                'message' => 'Success Logout'
            ]);
        } catch (\Exception $e) {
            return $this->sendFailRes($e);
        }
    }
}
