<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->sendRes([
            'user' => $request->user()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
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
