<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use App\Http\Controllers\Controller;
use Exception;

class CodeCheckController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string|exists:reset_code_passwords',
            ]);

            // find the code
            $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

            // check if it does not expired: the time is one hour
            if ($passwordReset->isExpired()) {
                throw new Exception('Code is expired', 422);
            }

            return $this->sendRes([
                'code' => $passwordReset->code,
                'message' => 'Code is valid'
            ]);
        } catch (Exception $e) {
            return $this->sendFailRes($e);
        }
    }
}
