<?php

// app/Http/Controllers/JurusanController.php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JurusanController extends Controller
{
    public function index(): JsonResponse
    {
        $jurusan = Jurusan::all();
        return response()->json($jurusan);
    }

    public function show(Jurusan $jurusan): JsonResponse
    {
        return response()->json($jurusan);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'jurusan' => 'required|max:20',
        ]);

        $jurusan = Jurusan::create($request->all());

        return response()->json($jurusan, 201);
    }
    public function update(Request $request, $id): JsonResponse
    {
        $jurusan = Jurusan::find($id);
    
        if (!$jurusan) {
            return response()->json(['error' => 'Jurusan tidak ditemukan'], 404);
        }
    
        $request->validate([
            'jurusan' => 'required|max:20',
        ]);
    
        $jurusan->update($request->all());
    
        return response()->json($jurusan, 200);
    }
    
    public function destroy($id): JsonResponse
    {
        $jurusan = Jurusan::find($id);
    
        if (!$jurusan) {
            return response()->json(['error' => 'Jurusan tidak ditemukan'], 404);
        }
    
        $jurusan->delete();
    
        return response()->json(null, 204);
    }
    
}
