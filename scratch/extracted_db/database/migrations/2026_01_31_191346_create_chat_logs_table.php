<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('chat_logs', function (Blueprint $table) {
        $table->id();
        // user_id boleh null (jika nanti ada fitur chat untuk tamu/guest)
        $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
        $table->text('pesan_user'); // Pertanyaan user
        $table->text('respon_ai');  // Jawaban AI
        $table->string('model_used')->default('Llama-3'); // Llama-3 atau Gemini
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_logs');
    }
};
