<?php

namespace App\Http\Controllers;
use App\Models\Pengembalian;

use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index()
{
    // Retrieve all pengembalian records with related peminjaman and permohonan data
    $pengembalians = Pengembalian::with(['peminjaman', 'peminjaman.permohonan'])->get();

    // Return the pengembalian data as JSON response
    return response()->json($pengembalians);
}


    public function destroy($id)
    {
        $pengembalian = pengembalian::find($id);

        if (!$pengembalian) {
            return response()->json(["message" => "Pengembalian tidak ditemukan"], 404);
        }

        $pengembalian->delete();

        return response()->json(["message" => "Pengembalian telah dihapus"]);
    }
}
