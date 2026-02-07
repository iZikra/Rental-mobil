<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            
            // Relasi: Cabang ini milik Rental mana?
            // onDelete('cascade') artinya jika Rental dihapus, semua cabang ikut terhapus
            $table->foreignId('rental_id')->constrained('rentals')->onDelete('cascade');
            
            // Detail Lokasi
            $table->string('nama_cabang'); // Contoh: "Cabang Pekanbaru Kota"
            $table->string('kota');        // Contoh: "Pekanbaru"
            $table->text('alamat_lengkap');// Alamat fisik lengkap
            $table->string('nomor_telepon_cabang'); // Kontak admin cabang
            
            // Fitur Tambahan (Opsional untuk Peta)
            $table->string('koordinat_lokasi')->nullable(); // Latitude, Longitude
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};