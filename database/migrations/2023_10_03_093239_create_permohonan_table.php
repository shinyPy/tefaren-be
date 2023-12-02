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
            $table->id('id');
            $table->enum('kesetujuan_syarat', ['setuju', 'tidak']);
            $table->bigInteger('id_pengguna')->unsigned();
            $table->foreign('id_pengguna')->references('id')->on('pengguna')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nomor_wa');

            $table->longText('details_barang');
            // $table->foreign('id_barang')->references('id')->on('barang')->onDelete('cascade')->onUpdate('cascade');

            $table->text('alasan_peminjaman');
            $table->date('tanggal_peminjaman');
            $table->string('lama_peminjaman');

            $table->string('nomor_peminjaman')->nullable();


            $table->enum('status_permohonan', ['diajukan','tolak', 'terima']);

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
