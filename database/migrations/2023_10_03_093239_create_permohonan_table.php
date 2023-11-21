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
        Schema::create('permohonan', function (Blueprint $table) {
            $table->id('id_permohonan');
            $table->enum('kesetujuan_syarat', ['setuju','tidak']);
            $table->string('nomorinduk_pengguna', 15)->unique();
            $table->foreign('nomorinduk_pengguna')->references('nomorinduk_pengguna')->on('pengguna');
    
            $table->string('email', 50)->index(); // Index added
            $table->foreign('email')->references('email')->on('pengguna');
    
            $table->string('nama_pengguna', 50)->index(); // Index added
            $table->foreign('nama_pengguna')->references('nama_pengguna')->on('pengguna');
    
            $table->enum('tipe_pengguna', ['siswa', 'guru'])->index(); // Index added
            $table->foreign('tipe_pengguna')->references('tipe_pengguna')->on('pengguna');


            $table->unsignedBigInteger('id_jurusan')->nullable();
            $table->foreign('id_jurusan')->references('id_jurusan')->on('pengguna')->onDelete('cascade');

            $table->string('kelas_pengguna');
            $table->string('nomor_wa');

            $table->unsignedBigInteger('id_jabatan')->nullable();
            $table->foreign('id_jabatan')->references('id_jabatan')->on('pengguna')->onDelete('cascade');

            $table->string('id_barang', 255)->unique();
            $table->foreign('nomor_barang')->references('nomor_barang')->on('barang')->onDelete('cascade');

            $table->string('nama_barang')->unique();

            $table->string('alasan_peminjaman', 100);
            $table->tinyInteger('jumlah_barang');
            $table->date('tanggal_peminjaman');
            $table->string('lama_peminjaman');
            $table->enum('status_peminjaman', ['tolak','terima','diajukan']);



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan');
    }
};
