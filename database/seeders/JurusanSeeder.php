<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    public function run()
    {
        $jurusanData = [
            ['jurusan' => 'rpl'],
            ['jurusan' => 'tjkt'],
            ['jurusan' => 'dkv'],
            ['jurusan' => 'animasi'],
            // Add more entries as needed
        ];

        Jurusan::insert($jurusanData);
    }
}
