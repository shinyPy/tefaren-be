<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id('nomor_barang'); // Use id() method to create auto-incrementing primary key
            $table->string('kode_barang', 25)->index('kode_barang');
            $table->string('nama_barang', 100)->index('nama_barang');
            $table->enum('ketersediaan_barang', ['Tersedia','Dipinjam','Pemeliharaan','Dihapuskan']);
            $table->enum('status_barang', ['baik','rusak']);
            $table->string('gambar_barang', 255);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
