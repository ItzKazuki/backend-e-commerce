<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginPage()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return view('admin.auth.login');
        }
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::Attempt($data)) {
            return redirect()->route('dashboard');
        }else{
            // Session::flash('error', 'Email atau Password Salah');
            return redirect()->route('auth.login')->with('error', 'Email atau Password Salah');
        }
    }
}
