<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id('id_pengguna'); // Use id() method to create auto-incrementing primary key
            $table->string('nomorinduk_pengguna', 15)->unique();
            $table->string('nama_pengguna');
            $table->enum('level_pengguna', ['user', 'admin']);
            $table->enum('tipe_pengguna', ['siswa', 'guru']);
            $table->unsignedBigInteger('id_jurusan')->nullable();
            $table->unsignedBigInteger('id_jabatan')->nullable();
            $table->string('email', 50)->unique();
            $table->string('password');
            $table->timestamps();

            $table->foreign('id_jurusan')->references('id_jurusan')->on('jurusan');
            $table->foreign('id_jabatan')->references('id_jabatan')->on('jabatan');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pengguna');
    }
};
