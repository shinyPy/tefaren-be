<?php

namespace App\Http\Controllers;
use App\Models\Pengembalian;

use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index()
    {
        // Retrieve all pengembalian records with related peminjaman data
        $pengembalians = Pengembalian::with('peminjaman')->get();

        // Return the pengembalian data as JSON response
        return response()->json(['pengembalians' => $pengembalians]);
    }
}