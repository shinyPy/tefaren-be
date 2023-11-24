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
        return response()->json(['permohonans' => $permohonans]);
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
            'nomorinduk_pengguna' => 'required|exists:pengguna,nomorinduk_pengguna',
            'email' => 'required|email|unique:permohonan,email',
            'nama_pengguna' => 'required|string|max:50',
            'tipe_pengguna' => 'required|in:siswa,guru',
            'id_jurusan' => 'nullable|exists:jurusan,id_jurusan',
            'kelas_pengguna' => 'required|string',
            'nomor_wa' => 'required|string',
            'id_jabatan' => 'nullable|exists:jabatan,id_jabatan',
            'id_barang' => 'required|exists:barang,id_barang',
            'nama_barang' => 'required|string|unique:permohonan,nama_barang',
            'alasan_peminjaman' => 'required|string|max:100',
            'jumlah_barang' => 'required|integer',
            'tanggal_peminjaman' => 'required|date',
            'lama_peminjaman' => 'required|string',
            'status_peminjaman' => 'required|in:tolak,terima,diajukan',
        ]);

        Permohonan::create($request->all());

        return response()->json(['message' => 'Permohonan created successfully.']);
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
        return response()->json(['permohonan' => $permohonan]);
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
            'kesetujuan_syarat' => 'required|in:setuju,tidak',
            'nomorinduk_pengguna' => 'required|exists:pengguna,nomorinduk_pengguna',
            'email' => 'required|email|unique:permohonan,email,' . $id,
            'nama_pengguna' => 'required|string|max:50',
            'tipe_pengguna' => 'required|in:siswa,guru',
            'id_jurusan' => 'nullable|exists:jurusan,id_jurusan',
            'kelas_pengguna' => 'required|string',
            'nomor_wa' => 'required|string',
            'id_jabatan' => 'nullable|exists:jabatan,id_jabatan',
            'id_barang' => 'required|exists:barang,id_barang',
            'nama_barang' => 'required|string|unique:permohonan,nama_barang,' . $id,
            'alasan_peminjaman' => 'required|string|max:100',
            'jumlah_barang' => 'required|integer',
            'tanggal_peminjaman' => 'required|date',
            'lama_peminjaman' => 'required|string',
            'status_peminjaman' => 'required|in:tolak,terima,diajukan',
        ]);

        $permohonan = Permohonan::find($id);
        $permohonan->update($request->all());

        return response()->json(['message' => 'Permohonan updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Permohonan::find($id)->delete();

        return response()->json(['message' => 'Permohonan deleted successfully']);
    }
}
