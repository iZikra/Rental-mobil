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
            $table->renameColumn('pesan_user', 'user_message');
            $table->renameColumn('respon_ai', 'bot_response');
            $table->string('session_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('rental_id')->nullable()->after('bot_response');
            
            // Note: rental_id is intentionally not constrained as a foreign key here to avoid complexity if rentals table is dropped/recreated
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_logs', function (Blueprint $table) {
            $table->renameColumn('user_message', 'pesan_user');
            $table->renameColumn('bot_response', 'respon_ai');
            $table->dropColumn('session_id');
            $table->dropColumn('rental_id');
        });
    }
};
