<?php

namespace App\Http\Controllers;
use App\Models\Barang; 
use App\Models\Pengguna; 
use App\Models\Permohonan; 
use App\Models\Pengembalian; 
use App\Models\Peminjaman; 

use Illuminate\Support\Facades\DB; // Add this line

class CountController extends Controller
{
    public function countBarang()
    {
        $totalBarang = Barang::count();

        return response()->json(['total' => $totalBarang]);
    }
    public function countPermohonan()
    {
        $totalPermohonan= Permohonan::count();

        return response()->json(['total' => $totalPermohonan]);
    }

    public function countPengembalian()
    {
        $totalPengembalian= Pengembalian::count();

        return response()->json(['total' => $totalPengembalian]);
    }

    public function countPeminjaman()
    {
        $totalPeminjaman= Peminjaman::count();

        return response()->json(['total' => $totalPeminjaman]);
    }
    
    
    
    public function countPengguna()
    {
        // Use the count() method to get the total count of "barang" records
        $totalPengguna = Pengguna::count();

        return response()->json(['total' => $totalPengguna]);
    }

    public function countTipePengguna()
{
    // Use the count() method to get the total count of "user" records
    $totalUsers = Pengguna::where('tipe_pengguna', 'siswa')->count();

    // Use the count() method to get the total count of "admin" records
    $totalAdmins = Pengguna::where('tipe_pengguna', 'guru')->count();

    return response()->json(['total_siswa' => $totalUsers, 'total_guru' => $totalAdmins]);
}

public function countTipeBarang()
{
    $totalTersedia = Barang::where('ketersediaan_barang', 'Tersedia')->count();

    $totalDipinjam = Barang::where('ketersediaan_barang', 'Dipinjam')->count();

    $totalPemeliharaan = Barang::where('ketersediaan_barang', 'Pemeliharaan')->count();

    $totalDihapuskan = Barang::where('ketersediaan_barang', 'Dihapuskan')->count();

    return response()->json(['total_baik' => $totalTersedia, 'total_rusak' => $totalDipinjam, 'total_pemeliharaan' => $totalPemeliharaan,'total_dihapuskan' => $totalDihapuskan]);
}

    public function countUsersByJurusan()
    {
        // Join the 'pengguna' and 'jurusan' tables based on the 'id_jurusan' foreign key
        // Count users by their associated 'jurusan'
        $userCountsByJurusan = DB::table('pengguna')
            ->join('jurusan', 'pengguna.id_jurusan', '=', 'jurusan.id_jurusan')
            ->select('jurusan.jurusan', DB::raw('count(*) as total'))
            ->groupBy('jurusan.jurusan')
            ->get();
    
        return response()->json(['userCountsByJurusan' => $userCountsByJurusan]);
    }
    
    public function countUsersByJabatan()
    {
        // Join the 'pengguna' and 'jurusan' tables based on the 'id_jurusan' foreign key
        // Count users by their associated 'jurusan'
        $userCountsByJabatan = DB::table('pengguna')
            ->join('jabatan', 'pengguna.id_jabatan', '=', 'jabatan.id_jabatan')
            ->select('jabatan.jabatan', DB::raw('count(*) as total'))
            ->groupBy('jabatan.jabatan')
            ->get();
    
        return response()->json(['userCountsByJabatan' => $userCountsByJabatan]);
    }
    public function countStatusBarang()
    {
      
    }
    
    

}
