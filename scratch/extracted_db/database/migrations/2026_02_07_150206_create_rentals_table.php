<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            
            // Pemilik Rental
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Identitas Bisnis
            $table->string('nama_rental'); 
            $table->string('slug')->unique(); 
            
            // TAMBAHAN: Nomor HP Bisnis
            $table->string('no_telp_bisnis')->nullable(); 
            
            // SOLUSI ERROR: Kolom alamat kita tambahkan disini
            $table->text('alamat')->nullable(); 
            
            $table->text('deskripsi')->nullable();
            $table->string('logo')->nullable(); 
            
            // Legalitas & Keuangan
            $table->string('no_izin_usaha')->nullable();
            $table->string('nomor_rekening')->nullable(); 
            $table->string('bank')->nullable(); 
            
            // Status
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};