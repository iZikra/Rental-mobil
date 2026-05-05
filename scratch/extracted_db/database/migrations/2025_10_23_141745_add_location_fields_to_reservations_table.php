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
    Schema::table('reservations', function (Blueprint $table) {
        $table->string('tipe_pengambilan')->default('kantor')->after('status');
        $table->text('alamat_pengambilan')->nullable()->after('tipe_pengambilan');
        $table->string('tipe_pengembalian')->default('kantor')->after('alamat_pengambilan');
        $table->text('alamat_pengembalian')->nullable()->after('tipe_pengembalian');
        $table->integer('biaya_tambahan')->default(0)->after('total_harga');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            //
        });
    }
};
