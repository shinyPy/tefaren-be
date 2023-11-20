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
                $table->id('id_peminjaman'); // Use id() method to create auto-incrementing primary key
                $table->string('nomor_peminjaman'); // Use id() method to create auto-incrementing primary key
                $table->string('nomorinduk_pengguna', 15)->unique();
                $table->string('nama_pengguna',100)->unique();
                $table->integer('nomor_barang')->unique(); // Remove auto_increment from this line
                $table->string('kode_barang', 15)->index('kode_barang');
                $table->string('status_barang')->unique();
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
