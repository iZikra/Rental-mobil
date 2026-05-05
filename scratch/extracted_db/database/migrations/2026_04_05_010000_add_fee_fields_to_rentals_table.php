<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            if (!Schema::hasColumn('rentals', 'biaya_sopir_per_hari')) {
                $table->unsignedInteger('biaya_sopir_per_hari')->default(0)->after('syarat_ketentuan');
            }
            if (!Schema::hasColumn('rentals', 'biaya_bandara_per_trip')) {
                $table->unsignedInteger('biaya_bandara_per_trip')->default(0)->after('biaya_sopir_per_hari');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            if (Schema::hasColumn('rentals', 'biaya_bandara_per_trip')) {
                $table->dropColumn('biaya_bandara_per_trip');
            }
            if (Schema::hasColumn('rentals', 'biaya_sopir_per_hari')) {
                $table->dropColumn('biaya_sopir_per_hari');
            }
        });
    }
};

