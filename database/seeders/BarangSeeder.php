<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     */
    public function run(): void
    {
        //
        Barang::Create([
            'nama_barang' => 'barang pertama',
            'kode_barang' => "093X",
            'id_kategori' => 1,
            'ketersediaan_barang' => 'Tersedia',
            'status_barang' => 'baik',
            'gambar_barang' => 'none'
        ]);
        Barang::Create([
            'nama_barang' => 'barang kedua',
            'kode_barang' => "093A",
            'id_kategori' => 1,
            'ketersediaan_barang' => 'Tersedia',
            'status_barang' => 'baik',
            'gambar_barang' => 'none'
        ]);
    }
}
