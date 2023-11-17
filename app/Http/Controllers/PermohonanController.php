<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permohonan;

class PermohonanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permohonans = Permohonan::all();

        return response()->json($permohonans);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'kesetujuan_syarat' => 'required|in:setuju,tidak',
            'nomorinduk_pengguna' => 'required|unique:permohonan|max:15',
            'nama_pengguna' => 'required|max:50',
            'tipe_pengguna' => 'required|in:siswa,guru',
            'id_jurusan' => 'exists:pengguna,id_jurusan|nullable',
            'kelas_pengguna' => 'required',
            'nomor_wa' => 'required',
            'id_jabatan' => 'exists:pengguna,id_jabatan|nullable',
            'nama_barang' => 'required|unique:permohonan',
            'alasan_peminjaman' => 'required|max:100',
            'jumlah_barang' => 'required|integer',
            'tanggal_peminjaman' => 'required|date',
            'lama_peminjaman' => 'required',
            'status_peminjaman' => 'required',
        ]);

        $permohonan = Permohonan::create($request->all());

        return response()->json($permohonan, 201);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permohonan = Permohonan::find($id);

        if (!$permohonan) {
            return response()->json(['message' => 'Permohonan not found'], 404);
        }

        return response()->json($permohonan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            // Add validation rules based on your table structure
        ]);

        $permohonan = Permohonan::find($id);

        if (!$permohonan) {
            return response()->json(['message' => 'Permohonan not found'], 404);
        }

        $permohonan->update($request->all());

        return response()->json($permohonan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permohonan = Permohonan::find($id);

        if (!$permohonan) {
            return response()->json(['message' => 'Permohonan not found'], 404);
        }

        $permohonan->delete();

        return response()->json(['message' => 'Permohonan deleted']);
    }
}
