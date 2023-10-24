<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        if (!$pengguna || !Hash::check($credentials['password'], $pengguna->password)) {
            return response()->json(["message" => "Email or password incorrect"], 401);
        }

        // Retrieve the user's role (level_pengguna)
        $userRole = $pengguna->level_pengguna;
        $userName = $pengguna->nama_pengguna;
        // Generate and store the token
        $token = $this->generateToken($pengguna);

        $request->attributes->add(['pengguna' => $pengguna, 'token' => $token, 'user_role' => $userRole, 'nama_pengguna' => $userName]);

        return $next($request);
    }

    private function generateToken($pengguna)
    {
        $token = Str::random(60);
    
        $pengguna->update(['api_token' => hash('sha256', $token)]);
    
        return $token;
    }
}
