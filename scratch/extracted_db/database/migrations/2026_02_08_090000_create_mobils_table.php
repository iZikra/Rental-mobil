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
        Schema::create('mobils', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Rental & Branch (Wajib ada)
            $table->foreignId('rental_id')->nullable()->constrained('rentals')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            
            // Info Utama
            $table->string('merk');  // Contoh: Toyota
            $table->string('model'); // Contoh: Avanza Veloz
            $table->string('no_plat')->unique(); // Sesuai permintaan Anda (no_plat)
            $table->decimal('harga_sewa', 15, 2);
            $table->string('status')->default('tersedia'); // tersedia, disewa
            
            // --- INI KOLOM YANG TADI HILANG ---
            $table->integer('tahun_buat');           // Error Anda tadi disini
            $table->string('transmisi');             // Manual / Matic
            $table->string('bahan_bakar');           // Bensin / Solar
            $table->integer('jumlah_kursi');         // 4 / 7
            $table->string('gambar')->nullable();    // Foto mobil
            $table->text('deskripsi')->nullable();   // Penjelasan singkat
            // ----------------------------------

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobils');
    }
};