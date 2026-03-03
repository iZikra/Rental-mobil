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
            
            // Relasi Arsitektur Multi-Tenant
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mobil_id')->constrained('mobils')->onDelete('cascade');
            $table->foreignId('rental_id')->constrained('rentals')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            
            // Data Pribadi Penyewa (Sinkron dengan $fillable)
            $table->string('nama')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto_identitas')->nullable(); // Wajib ada untuk KTP
            
            // Detail Perjalanan (Sesuai keinginan Anda: tgl_ambil)
            $table->date('tgl_ambil');
            $table->time('jam_ambil');
            $table->date('tgl_kembali');
            $table->time('jam_kembali');
            $table->string('lokasi_ambil')->nullable();
            $table->string('lokasi_kembali')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->text('alamat_jemput')->nullable();
            $table->text('alamat_antar')->nullable();
            $table->string('tujuan')->nullable();
            
            // Kalkulasi & Opsi
            $table->enum('sopir', ['dengan_sopir', 'tanpa_sopir'])->default('tanpa_sopir');
            $table->integer('lama_sewa')->default(1);
            $table->decimal('total_harga', 15, 2);
            $table->string('bukti_bayar')->nullable();
            
            // Status (Saya ubah menjadi Title Case agar cocok dengan Controller Anda)
            $table->enum('status', ['Pending', 'Dibayar', 'Menunggu Konfirmasi', 'Dikonfirmasi', 'Sedang Jalan', 'Selesai', 'Dibatalkan', 'Ditolak'])->default('Pending');
            $table->text('catatan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};