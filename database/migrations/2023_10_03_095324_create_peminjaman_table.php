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
            Schema::create('peminjaman', function (Blueprint $table) {
                $table->id('id_peminjaman');
                $table->unsignedBigInteger('id_permohonan')->unique();
                $table->string('nomor_peminjaman', 3)->unique();
                $table->string('nomorinduk_pengguna', 15)->unique();
                $table->string('nama_pengguna', 100)->unique();
                $table->integer('nomor_barang')->unique();
                $table->string('kode_barang', 15)->index('kode_barang');
                $table->enum('status_barang', ['baik', 'rusak'])->unique();
                $table->enum('status_peminjaman', ['dipinjam', 'dikembalikan']);
    
                $table->foreign('id_permohonan')->references('id_permohonan')->on('permohonan')->onDelete('cascade');
            });
        }
        

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('peminjaman');
        }
    };
