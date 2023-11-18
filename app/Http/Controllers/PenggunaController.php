<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    public function index()
{
    $penggunaList = Pengguna::with(['jabatan:id_jabatan,jabatan', 'jurusan:id_jurusan,jurusan'])
        ->select(['nomorinduk_pengguna', 'nama_pengguna', 'level_pengguna', 'id_jurusan', 'id_jabatan'])
        ->get()
        ->transform(function ($pengguna) {
            $data = collect($pengguna)->except(['jabatan', 'jurusan']);

            if ($pengguna->jabatan !== null) {
                $jabatan = [
                    'id_jabatan' => data_get($pengguna->jabatan, 'id_jabatan'),
                    'jabatan' => data_get($pengguna->jabatan, 'jabatan'),
                ];
                $data->put('jabatan', array_filter($jabatan, fn ($value) => $value !== null));
            }

            if ($pengguna->jurusan !== null) {
                $jurusan = [
                    'id_jurusan' => data_get($pengguna->jurusan, 'id_jurusan'),
                    'jurusan' => data_get($pengguna->jurusan, 'jurusan'),
                ];
                $data->put('jurusan', array_filter($jurusan, fn ($value) => $value !== null));
            }

            return $data->filter(); // Filter out null values from the entire collection
        });

    return response()->json($penggunaList);
}

    
    
    

    
    
    public function show($id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json(["message" => "Pengguna not found"], 404);
        }

        return response()->json($pengguna);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomorinduk_pengguna' => 'required|unique:pengguna,nomorinduk_pengguna|max:15',
            'nama_pengguna' => 'required',
            'level_pengguna' => 'required|in:user,admin',
            'tipe_pengguna' => 'required|in:siswa,guru',
            'id_jurusan' => 'nullable|exists:jurusan,id_jurusan',
            'id_jabatan' => 'nullable|exists:jabatan,id_jabatan',
            'email' => 'required|email|unique:pengguna,email|max:50',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => "Invalid field", "errors" => $validator->errors()], 422);
        }

        $pengguna = new Pengguna([
            'nomorinduk_pengguna' => $request->nomorinduk_pengguna,
            'nama_pengguna' => $request->nama_pengguna,
            'level_pengguna' => $request->level_pengguna,
            'tipe_pengguna' => $request->tipe_pengguna,
            'id_jurusan' => $request->id_jurusan,
            'id_jabatan' => $request->id_jabatan,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $pengguna->save();

        return response()->json(["message" => "Pengguna added successfully"], 201);
    }

    public function update(Request $request, $id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json(["message" => "Pengguna not found"], 404);
        }

        $validator = Validator::make($request->all(), [
            'nomorinduk_pengguna' => 'required|unique:pengguna,nomorinduk_pengguna,' . $id . ',id_pengguna|max:15',
            'nama_pengguna' => 'required',
            'level_pengguna' => 'required|in:user,admin',
            'tipe_pengguna' => 'required|in:siswa,guru',
            'id_jurusan' => 'nullable|exists:jurusan,id_jurusan',
            'id_jabatan' => 'nullable|exists:jabatan,id_jabatan',
            'email' => 'required|email|unique:pengguna,email,' . $id . ',id_pengguna|max:50',
            'password' => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => "Invalid field", "errors" => $validator->errors()], 422);
        }

        // Update only the fields that are present in the request
        $pengguna->fill($request->only([
            'nomorinduk_pengguna',
            'nama_pengguna',
            'level_pengguna',
            'tipe_pengguna',
            'id_jurusan',
            'id_jabatan',
            'email',
        ]));

        // Update password if provided
        if ($request->has('password')) {
            $pengguna->password = Hash::make($request->password);
        }

        $pengguna->save();

        return response()->json(["message" => "Pengguna updated successfully"]);
    }

    public function destroy($id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json(["message" => "Pengguna not found"], 404);
        }

        $pengguna->delete();

        return response()->json(["message" => "Pengguna deleted successfully"]);
    }
}
