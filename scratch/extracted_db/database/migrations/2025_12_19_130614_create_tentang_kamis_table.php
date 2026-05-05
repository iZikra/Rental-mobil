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
    Schema::create('tentang_kamis', function (Blueprint $table) {
        $table->id();
        $table->string('judul'); // Contoh: "Visi Kami"
        $table->text('isi');     // Contoh: "Menjadi rental terbaik..."
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tentang_kamis');
    }
};
