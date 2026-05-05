<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // ATURAN MUTLAK: Harus nullable() karena Pelanggan biasa tidak punya Rental
            $table->foreignId('rental_id')->nullable()->constrained('rentals')->onDelete('cascade');
            
            // Opsional tapi sangat disarankan: Tambahkan kolom role jika Anda belum punya
            // $table->enum('role', ['admin_sistem', 'mitra', 'customer'])->default('customer');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rental_id']);
            $table->dropColumn('rental_id');
        });
    }
};