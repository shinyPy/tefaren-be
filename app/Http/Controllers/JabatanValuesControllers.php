<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;

class JabatanValuesControllers extends Controller
{
    public function getJabatanValues()
    {
        $jurusanValues = Jabatan::pluck('jabatan');
    
        return response()->json($jurusanValues);
    }
}