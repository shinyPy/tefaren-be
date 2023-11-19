<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Jurusan; // Add the Jurusan model
use App\Models\Jabatan; // Add the Jabatan model
class PenggunaController extends Controller
{
    public function index()
    {
        $penggunaList = Pengguna::with(['jabatan:id_jabatan,jabatan', 'jurusan:id_jurusan,jurusan'])
            ->select(['nomorinduk_pengguna', 'nama_pengguna', 'level_pengguna', 'id_jurusan', 'id_jabatan', 'email'])
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

    public function update(Request $request, $nomorinduk_pengguna)
    {
        // Find the Pengguna based on nomorinduk_pengguna
        $pengguna = Pengguna::where('nomorinduk_pengguna', $nomorinduk_pengguna)->first();
    
        // Check if Pengguna exists
        if (!$pengguna) {
            return response()->json(["message" => "Pengguna not found"], 404);
        }
    
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'nomorinduk_pengguna' => 'required|unique:pengguna,nomorinduk_pengguna,' . $nomorinduk_pengguna . ',nomorinduk_pengguna|max:15',
            'nama_pengguna' => 'required',
            'level_pengguna' => 'required|in:user,admin',
            'id_jurusan' => 'nullable|exists:jurusan,id_jurusan',
            'id_jabatan' => 'nullable|exists:jabatan,id_jabatan',
            'email' => 'required|email|unique:pengguna,email,' . $nomorinduk_pengguna . ',nomorinduk_pengguna|max:50',
            'jurusan_pengguna' => 'nullable|string|exists:jurusan,jurusan',
            'jabatan_pengguna' => 'nullable|string|exists:jabatan,jabatan',
        ]);
    
        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json(["message" => "Invalid field", "errors" => $validator->errors()], 422);
        }
    
        // Update nomorinduk_pengguna separately, as it's used in the unique validation rule
        $pengguna->nomorinduk_pengguna = $request->nomorinduk_pengguna;
    
        // Update other fields
        $pengguna->nama_pengguna = $request->nama_pengguna;
        $pengguna->level_pengguna = $request->level_pengguna;
        $pengguna->id_jurusan = $request->filled('id_jurusan') ? $request->id_jurusan : $pengguna->id_jurusan;
        $pengguna->id_jabatan = $request->filled('id_jabatan') ? $request->id_jabatan : $pengguna->id_jabatan;
        $pengguna->email = $request->email;
    
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
    
        // Save the changes
        $pengguna->save();
    
        return response()->json(["message" => "Pengguna updated successfully"]);
    }
    
    

public function destroy($nomorinduk_pengguna)
{
    $pengguna = Pengguna::where('nomorinduk_pengguna', $nomorinduk_pengguna)->first();

    if (!$pengguna) {
        return response()->json(["message" => "Pengguna not found"], 404);
    }

    $pengguna->delete();

    return response()->json(["message" => "Pengguna deleted successfully"]);
}
}
