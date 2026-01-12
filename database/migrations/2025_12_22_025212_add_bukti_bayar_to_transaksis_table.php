<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Kita tambahkan kolom baru "bukti_bayar" yang boleh kosong (nullable)
            // Kita letakkan setelah kolom "total_harga" agar rapi
            $table->string('bukti_bayar')->nullable()->after('total_harga');
        });
    }

    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Perintah untuk menghapus kolom jika migrasi dibatalkan
            $table->dropColumn('bukti_bayar');
        });
    }
};
