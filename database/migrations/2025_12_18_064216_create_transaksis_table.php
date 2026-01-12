<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            // Menyimpan siapa yang sewa
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Menyimpan mobil apa yang disewa
            $table->foreignId('mobil_id')->constrained('mobils')->onDelete('cascade');
            
            // Data Pemesan
            $table->string('nama');
            $table->string('no_hp');
            $table->text('alamat_jemput');
            $table->text('alamat_antar');
            
            // Data Sewa
            $table->date('tgl_ambil');
            $table->date('tgl_kembali');
            $table->string('jam_ambil');
            $table->string('jam_kembali');
            
            // Opsi Tambahan
            $table->boolean('pakai_sopir')->default(false);
            $table->decimal('total_harga', 15, 2);
            
            // Status: pending, process, finished, rejected
            $table->string('status')->default('pending');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
};