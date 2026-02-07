<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            
            // Relasi User (Penyewa)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Relasi Mobil
            $table->foreignId('mobil_id')->constrained('mobils')->onDelete('cascade');
            
            // Relasi Multi-Rental (Agar uang masuk ke rental yang benar)
            $table->foreignId('rental_id')->constrained('rentals')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            
            // Detail Sewa
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->decimal('total_harga', 15, 2);
            
            // Bukti Bayar (Ini yang tadi error, sekarang kita masukkan langsung disini)
            $table->string('bukti_bayar')->nullable(); 
            
            // Status Transaksi
            $table->enum('status', ['pending', 'dibayar', 'dikonfirmasi', 'selesai', 'batal'])->default('pending');
            $table->text('catatan')->nullable(); // Opsional: misal "Supir merokok"
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};