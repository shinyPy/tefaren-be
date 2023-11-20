<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pengguna;
use App\Models\Jurusan; // Add the Jurusan model
use App\Models\Jabatan; // Add the Jabatan model

class AuthController extends Controller
{
    /**
     * User registration
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required',
            'nomorinduk_pengguna' => 'required|max:13', // Add max:13 for a maximum length of 13 characters
            'nama_pengguna' => 'required',
            'tipe_pengguna' => 'required|in:siswa,guru,staff',
            'jurusan_pengguna' => 'sometimes|in:non,rpl,tjkt,dkv,animasi',
            'jabatan_pengguna' => 'sometimes|in:non,administrasi,produktif,nonproduktif,perpustakaan',
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
        
            // Check for the specific error related to nomorinduk_pengguna length
            if ($errors->has('nomorinduk_pengguna') && $errors->first('nomorinduk_pengguna') === 'The nomorinduk_pengguna may not be greater than 13 characters.') {
                return response()->json(["message" => $errors->first('nomorinduk_pengguna')], 422);
            }
        
            // Check for the email uniqueness error
            if ($errors->has('email') && $errors->first('email') === 'The email has already been taken.') {
                return response()->json(["message" => "Email already used"], 422);
            }
        
            return response()->json(["message" => "Invalid field", "errors" => $errors], 422);
        }
        
        
    
        $pengguna = new Pengguna();
        $pengguna->nama_pengguna = $request->nama_pengguna;
        $pengguna->nomorinduk_pengguna = $request->nomorinduk_pengguna;
        $pengguna->email = $request->email;
        $pengguna->password = bcrypt($request->password);
        $pengguna->tipe_pengguna = $request->tipe_pengguna;
    
        // Check if Jurusan exists, and associate the user with it
        if ($request->has('jurusan_pengguna')) {
            $jurusan = Jurusan::where('jurusan', $request->jurusan_pengguna)->first();
            if ($jurusan) {
                $pengguna->id_jurusan = $jurusan->id_jurusan; // Use the 'id_jurusan' column for the association
            }
        }
    
        // Check if Jabatan exists, and associate the user with it
        if ($request->has('jabatan_pengguna')) {
            $jabatan = Jabatan::where('jabatan', $request->jabatan_pengguna)->first();
            if ($jabatan) {
                $pengguna->id_jabatan = $jabatan->id_jabatan; // Use the 'id_jabatan' column for the association
            }
        }
    
        $pengguna->save();
    
        return response()->json([
            "message" => "Register success",
            "user" => [
                "nomorinduk_pengguna" => $pengguna->nomorinduk_pengguna,
                "email" => $pengguna->email,
                "tipe_pengguna" => $pengguna->tipe_pengguna,
                "id_jurusan" => $pengguna->id_jurusan,
                "id_jabatan" => $pengguna->id_jabatan,
            ]
        ]);
    }
    
    public function login(Request $request)
{
    // Middleware will handle authentication and token generation
    $pengguna = $request->get('pengguna');
    $token = $request->get('token');
    $userLevel = Pengguna::where('email', $pengguna->email)->value('level_pengguna');
    $userName = Pengguna::where('email', $pengguna->email)->value('nama_pengguna');
    return response()->json([
        "message" => "Login success",
        "user" => [
            'nama_pengguna' => $userName,
            'nomorinduk_pengguna' => $pengguna->nomorinduk_pengguna,
            'email' => $pengguna->email,
            'level_pengguna' => $userLevel, // Include the user's role in the response

            'accessToken' => $token
        ]
    ]);
}

    
    // Helper method to generate a token for Pengguna

    /**
     * User logout
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // Retrieve the authenticated user from the request
            $user = $request->user();

            if ($user) {
                // Revoke the user's tokens
                $user->tokens()->delete();

                return response()->json([
                    "message" => "Logout success"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Unauthenticated"
                ], 401);
            }
        } catch (\Exception $e) {
            // Handle any exceptions that may occur
            return response()->json([
                "message" => "Logout failed",
                "error" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get the authenticated user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}