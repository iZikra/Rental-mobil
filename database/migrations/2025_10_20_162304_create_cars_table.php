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
    Schema::create('cars', function (Blueprint $table) {
        $table->id();
        $table->string('nama_mobil');
        $table->string('slug')->unique();
        $table->string('gambar');
        $table->integer('harga_sewa');
        $table->string('bahan_bakar');
        $table->integer('jumlah_kursi');
        $table->string('transmisi');
        $table->enum('status', ['tersedia', 'tidak tersedia'])->default('tersedia');
        $table->text('deskripsi');
        $table->string('p3k');
        $table->string('charger');
        $table->string('ac');
        $table->string('audio');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
