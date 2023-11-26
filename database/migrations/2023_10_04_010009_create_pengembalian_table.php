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
            $table->id('id');

            $table->bigInteger('id_peminjaman')->unsigned();
            $table->foreign('id_peminjaman')->references('id')->on('peminjaman')->onDelete('cascade')->onUpdate('cascade');

            $table->enum('status_barang', ['baik', 'rusak']);
            $table->date('tanggal_pengembalian');
            $table->text('bukti_pengembalian');
            $table->enum('status_pengembalian', ['dikembalikan', 'dicek']);

            $table->timestamps();
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
