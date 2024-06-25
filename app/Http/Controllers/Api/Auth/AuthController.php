<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @group Auth
 *
 * API for Authorization process
 */
class AuthController extends Controller
{
    /**
     * Register new Account
     *
     * This endpoint lets you register new user.
     * @bodyParam name string required for name your account. Example: Yani
     * @bodyParam email string required for find account in database. Example: yani@account.com
     * @bodyParam phone string required for add number phone to account. Example: 628667788990
     * @bodyParam password string required for authorize account. Example: password
     * @bodyParam address string for add home address to account. Example: jln. kemang raya 66
     */
    public function register(Request $request) {
        try {
            $userData = $request->validate([
                'name' => 'required|min:3|max:70',
                'email' => 'required|email|string|unique:users',
                'phone' => 'required|string|unique:users',
                'password' => 'required|min:3'
            ]);

            // manipulasi object di php
            $userData['password'] = Hash::make($request->input('password'));

            User::create($userData);

            return $this->sendRes([
                'message' => 'success create new account, please login first.'
            ]);

        } catch (\Exception $e) {
            return $this->sendFailRes($e);
        }
    }

    /**
     * Login to your Account
     *
     * This endpoint lets you login to your account.
     * @bodyParam email string required for find account in database. Example: ucup@account.com
     * @bodyParam password string required for authorize account. Example: password
     */
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

    /**
     * Logut to your Account
     *
     * This endpoint lets you logout to your account.
     * @authenticated
     */
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
