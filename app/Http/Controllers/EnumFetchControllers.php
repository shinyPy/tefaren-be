<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;

class EnumFetchControllers extends Controller
{
    public function getJurusanValues()
    {
        $jurusanValues = Jurusan::pluck('jurusan');
    
        return response()->json($jurusanValues);
    }
}
