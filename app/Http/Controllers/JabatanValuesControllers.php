<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Kategori;

class JabatanValuesControllers extends Controller
{
    public function getJabatanValues()
    {
        $jurusanValues = Jabatan::pluck('jabatan');
    
        return response()->json($jurusanValues);
    }

    public function getKategoriValues()
    {
        $kategoriValues = Kategori::pluck('kategori');
    
        return response()->json($kategoriValues);
    }
}