<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    public function run()
    {
        $jabatanData = [
            ['jabatan' => 'produktif'],
            ['jabatan' => 'nonproduktif'],
            // Add more entries as needed
        ];

        Jabatan::insert($jabatanData);
    }
}

