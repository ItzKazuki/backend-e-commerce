<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string|exists:reset_code_passwords',
                'password' => 'required|string|min:6|confirmed',
            ]);

            // find the code
            $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

            // check if it does not expired: the time is one hour
            if ($passwordReset->isExpired()) {
                throw new Exception('Code is expired', 422);
            }

            // find user's email
            $user = User::firstWhere('email', $passwordReset->email);

            // update user password
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // delete current code
            $passwordReset->delete();

            return $this->sendRes(['message' => 'your password has been successfully reset, please login again']);
        } catch (Exception $e) {
            return $this->sendFailRes($e);
        }
    }
}
