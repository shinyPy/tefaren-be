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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id('id_pengembalian'); // Use id() method to create auto-incrementing primary key
            $table->integer('id_peminjaman')->unique(); // Use id() method to create auto-incrementing primary key
            $table->string('nomorinduk_pengguna', 15)->unique();
            $table->string('nama_pengguna',100)->unique();
            $table->integer('nomor_barang')->unique(); // Remove auto_increment from this line
            $table->string('kode_barang', 15)->index('kode_barang');
            $table->enum('status_barang', ['baik','rusak'])->unique();
            $table->date('tanggal_pengembalian');
            $table->string('bukti_pengembalian',100);
            $table->enum('status_pengembalian', ['dikembalikan', 'dicek']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
