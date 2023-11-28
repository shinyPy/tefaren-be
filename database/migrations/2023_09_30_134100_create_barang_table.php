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
            $table->id('id_barang');
            $table->string('kode_barang')->unique();
            $table->unsignedBigInteger('id_kategori')->nullable(); // Use unsignedBigInteger for foreign keys
            $table->string('nama_barang')->index();
            $table->enum('ketersediaan_barang', ['Tersedia','Dipinjam','Pemeliharaan','Dihapuskan']);
            $table->enum('status_barang', ['baik', 'rusak']);
            $table->string('gambar_barang', 255);

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_barang')->onDelete('cascade');

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
