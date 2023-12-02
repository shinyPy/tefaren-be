<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Permohonan;
use Illuminate\Database\Seeder;

class PermohonanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Permohonan::Create([
            'kesetujuan_syarat' => 'setuju',
            'id_pengguna' => 1,
            'nomor_wa' => "081378327060",
            'details_barang' => json_encode(array(
                array('id' => 1),
                array('id' => 2)
            )),
            'alasan_peminjaman' => 'saya tidak tau yang mulia',
            'tanggal_peminjaman' => '2006-07-05',
            'lama_peminjaman' => 'tidak tau yang mulia',
            'status_permohonan' => 'diajukan',
            'nomor_peminjaman' => '3862',
        ]);
    }
}
