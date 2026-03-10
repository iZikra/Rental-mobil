<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah ENUM menjadi VARCHAR agar tidak kaku lagi
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('status', 50)->default('pending')->change();
        });
    }

    public function down(): void
    {
        // Jika ingin kembali ke ENUM
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN status ENUM('pending', 'dibayar', 'selesai') NOT NULL DEFAULT 'pending'");
    }
};