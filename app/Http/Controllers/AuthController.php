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
            'nomorinduk_pengguna' => 'required|unique:pengguna,nomorinduk_pengguna|max:13', // Add max:13 for a maximum length of 13 characters
            'nama_pengguna' => 'required',
            'tipe_pengguna' => 'required|in:siswa,guru,staff',
            'jurusan_pengguna' => 'sometimes|exists:jurusan,jurusan',
            'jabatan_pengguna' => 'sometimes|exists:jabatan,jabatan',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            // Check for the specific error related to nomorinduk_pengguna length
            if ($errors->has('nomorinduk_pengguna') && $errors->first('nomorinduk_pengguna') === 'The nomorinduk_pengguna may not be greater than 13 characters.') {
                return response()->json(["message" => "Nomor Induk melebihi batas"], 422);
            }

            // Check for the email uniqueness error
            if ($errors->has('email') && $errors->first('email') === 'The email has already been taken.') {
                return response()->json(["message" => "Email telah dipakai"], 422);
            }

            // Check for the nomorinduk_pengguna uniqueness error
            if ($errors->has('nomorinduk_pengguna') && $errors->first('nomorinduk_pengguna') === 'The nomorinduk_pengguna has already been taken.') {
                return response()->json(["message" => "Nomor Induk telah dipakai"], 422);
            }

            return response()->json(["message" => "Field invalid.", "errors" => $errors], 422);
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
            "user" => $pengguna
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'sometimes|required|email', // If 'email' is present, validate it
            'nomorinduk_pengguna' => 'sometimes|required|string', // If 'nomorinduk_pengguna' is present, validate it
            'password' => 'required|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()]);
        }
    
        if ($request->has('email')) {
            $credentials = $request->only('email', 'password');
            $field = 'email';
        } else {
            $credentials = $request->only('nomorinduk_pengguna', 'password');
            $field = 'nomorinduk_pengguna';
        }
    
        $token = auth()->attempt($credentials);
    
        if (!$token) {
            return response()->json(['success' => false, 'message' => "$field or Password Anda Salah!"]);
        } else {
            $user = auth()->user()->with('jabatan', 'jurusan')->get();
        }
    
        return response()->json([
            'success' => true,
            'pengguna' => auth()->guard('api')->user(),
            'accessToken' => $token
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
        $user = $request->user()->with('jabatan', 'jurusan')->get();
        return response()->json([
            'user' => $user,
        ]);
    }

    public function checkToken()
    {
        return response()->json(['success' => true]);
    }
}
