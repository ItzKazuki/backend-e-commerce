<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->role == \App\Models\User::ADMIN) {
            return $next($request);
        }

        if(auth()->user()->role != \App\Models\User::ADMIN) {
            return abort(401);
        }

        if(Auth::guard('api')->check()) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }

        return redirect()->route('auth.login');
    }
}
