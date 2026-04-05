<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('mobils', 'tipe_mobil')) {
            Schema::table('mobils', function (Blueprint $table) {
                $table->string('tipe_mobil')->nullable()->after('harga_sewa');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('mobils', 'tipe_mobil')) {
            Schema::table('mobils', function (Blueprint $table) {
                $table->dropColumn('tipe_mobil');
            });
        }
    }
};
