<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Check the user's 'level_pengguna' field
            if ($user->level_pengguna === 'admin') {
                // User has the required level, allow access
                return $next($request);
            }
        }

        // User does not have the required level, redirect or return an error response
        return response()->json(['message' => 'You are not Admin!'], 401);
    }
}
