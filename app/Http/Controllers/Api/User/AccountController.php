<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @group Account
 *
 * API for view, edit, or delete account.
 * @authenticated
 */

class AccountController extends Controller
{
    /**
     * Show user details
     *
     * show your account details
     */
    public function index(Request $request)
    {
        return $this->sendRes([
            'user' => $request->user()
        ]);
    }

    /**
     * Update account details
     *
     * you can update account detail here with spesific request.
     * @urlParam id required The id user. Example: user-ajnav
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove Account
     *
     * this API for remove your account from database.
     */
    public function destroy(Request $request)
    {
        // delete account
        try {
            $request->user()->delete();

            return $this->sendRes([
                'message' => 'Account deleted successfully'
            ]);
        } catch(\Exception $e) {
            return $this->sendFailRes($e, 400);
        }
    }
}
