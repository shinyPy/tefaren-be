<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLevelPengguna
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        if (auth()->check()) {
            $user = auth()->user();
            
            // Check the user's 'level_pengguna' field
            if ($user->level_pengguna === 'admin') {
                // User has the required level, allow access
                return $next($request);
            }
        }

        // User does not have the required level, redirect or return an error response
        return response('Unauthorized', 401);
    }
}
