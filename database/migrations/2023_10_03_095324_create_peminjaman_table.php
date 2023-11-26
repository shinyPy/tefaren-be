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
                $table->id('id')->primary();
                $table->bigInteger('id_permohonan')->unsigned();
                $table->foreign('id_permohonan')->references('id')->on('permohonan')->onDelete('cascade')->onUpdate('cascade');

                $table->enum('status_barang', ['baik', 'rusak']);
                $table->enum('status_peminjaman', ['dipinjam', 'dikembalikan']);
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
