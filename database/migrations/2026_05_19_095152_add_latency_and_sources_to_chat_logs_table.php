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
        Schema::table('chat_logs', function (Blueprint $table) {
            $table->decimal('latency', 8, 2)->nullable()->after('model_used')->comment('Waktu respons dalam milidetik');
            $table->json('context_sources')->nullable()->after('latency')->comment('Daftar sumber dokumen yang digunakan AI');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_logs', function (Blueprint $table) {
            $table->dropColumn(['latency', 'context_sources']);
        });
    }
};
