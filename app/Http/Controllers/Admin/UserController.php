<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userData = $request->validate([
            'name' => 'required|min:3|max:60',
            'email' => 'required|email',
            'phone' => 'required|string',
            'password' => 'required',
            'role' => 'required'
        ]);

        $userData['password'] = Hash::make($request->password);

        User::create($userData);

        return redirect()->route('admin.users.index')->with('success', 'Success Create New Account');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $userData = $request->validate([
            'name' => 'required|min:3|max:60',
            'email' => 'required|email',
            'phone' => 'required|string',
            'password' => '',
            'role' => 'required'
        ]);

        if(isset($userData['password'])) {
            $userData['password'] = Hash::make($request->password);
            $user->password = $userData['password'];
        }

        $user->name = $userData['name'];
        $user->email = $userData['email'];
        $user->phone = $userData['phone'];
        $user->role = $userData['role'];

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Success Update Account');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Success Delete User');
    }
}
