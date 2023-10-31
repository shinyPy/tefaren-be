<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthenticateUser
{
    public function handle($request, Closure $next)
    {
        // Check if 'email' is present in the request
        if ($request->has('email')) {
            $credentials = $request->only('email', 'password');
            $field = 'email';
        } else {
            $credentials = $request->only('nomorinduk_pengguna', 'password');
            $field = 'nomorinduk_pengguna';
        }

        $pengguna = Pengguna::where($field, $credentials[$field])->first();

        if (!$pengguna || !\Hash::check($credentials['password'], $pengguna->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Anda salah'
            ], 401);
        }

        // Use Laravel Passport for token authentication
        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Anda salah'
            ], 401);
        }

        // If authentication is successful
        return response()->json([
            'success' => true,
            'pengguna' => auth()->guard('api')->user(),
            'token' => $token,
        ], 200);
    }
}
