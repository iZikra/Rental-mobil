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
    Schema::table('cars', function (Blueprint $table) {
        // Kolom info tambahan dari gambar
        $table->string('type')->nullable()->after('merek'); // Contoh: Small MPV

        // Kolom Harga Mobil Saja
        $table->integer('harga_mobil_12h')->nullable()->default(0)->after('harga_sewa');
        $table->integer('harga_mobil_24h')->nullable()->default(0)->after('harga_mobil_12h');

        // Kolom Harga Mobil + Driver
        $table->integer('harga_driver_12h')->nullable()->default(0)->after('harga_mobil_24h');
        $table->integer('harga_driver_24h')->nullable()->default(0)->after('harga_driver_12h'); // Asumsi Per Day = 24 Jam

        // Kolom Mobil + Sopir + Bensin
        $table->integer('harga_bbm_4h')->nullable()->default(0)->after('harga_driver_24h');
        $table->integer('harga_bbm_12h')->nullable()->default(0)->after('harga_bbm_4h');
        $table->integer('harga_bbm_24h')->nullable()->default(0)->after('harga_bbm_12h'); // Asumsi Per Day = 24 Jam

        // Kolom Mobil + Sopir + Bensin + Parkir + Makan Sopir
        $table->integer('harga_allin_4h')->nullable()->default(0)->after('harga_bbm_24h');
        $table->integer('harga_allin_6h')->nullable()->default(0)->after('harga_allin_4h');
        $table->integer('harga_allin_12h')->nullable()->default(0)->after('harga_allin_6h');
        $table->integer('harga_allin_24h')->nullable()->default(0)->after('harga_allin_12h'); // Asumsi Per Day = 24 Jam
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            //
        });
    }
};
