<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(JabatanSeeder::class);
        $this->call(JurusanSeeder::class);
        $this->call(KategoriSeeder::class);
        // $this->call(Permohonan::class);
        Pengguna::Create([
            'nomorinduk_pengguna' => 231,
            'nama_pengguna' => 'Admin',
            'tipe_pengguna' => 'guru',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
        ]);
    }
}
