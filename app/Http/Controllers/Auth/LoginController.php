<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginPage()
    {
        if (Auth::check()) {
            if (auth()->user()->role == User::SELLER) return redirect()->route('seller.dashboard');
            if (auth()->user()->role == User::ADMIN) return redirect()->route('dashboard');
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
            // dd(Auth::check());
            if (auth()->user()->role == User::SELLER) return redirect()->route('seller.dashboard');
            if (auth()->user()->role == User::ADMIN) return redirect()->route('dashboard');
        }
        // Session::flash('error', 'Email atau Password Salah');
        return redirect()->back()->with('error', 'Email atau Password Salah');
    }
}
