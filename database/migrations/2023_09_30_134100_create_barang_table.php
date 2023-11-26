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
            $table->id('id')->primary();
            $table->string('kode_barang')->unique();
            $table->unsignedBigInteger('id_kategori');
            $table->string('nama_barang');
            $table->enum('ketersediaan_barang', ['Tersedia', 'Dipinjam', 'Pemeliharaan', 'Dihapuskan']);
            $table->enum('status_barang', ['baik', 'rusak']);
            $table->string('gambar_barang', 255);

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_barang')->onDelete('cascade')->onUpdate('cascade');

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
