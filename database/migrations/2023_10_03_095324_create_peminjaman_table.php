    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up()
        {
            Schema::create('peminjaman', function (Blueprint $table) {
                $table->id('id_peminjaman');
                $table->bigInteger('id_permohonan')->unsigned();
                $table->foreign('id_permohonan')->references('id')->on('permohonan')->onDelete('cascade')->onUpdate('cascade');
    
                $table->bigInteger('id_barang')->unsigned();
                $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade')->onUpdate('cascade');
    
                $table->enum('status_peminjaman', ['dipinjam', 'dikembalikan']);
                // Add any other columns you need for Peminjaman
    
                $table->timestamps();
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
