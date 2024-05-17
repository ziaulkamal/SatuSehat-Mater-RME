<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ConditionalAuthPermission
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user_id') && !Session::has('condition')) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access',
                ], 401);
            } else {
                return redirect('/login')->with('error', 'You must log in first.');
            }
        }


        return $next($request);
    }
}
