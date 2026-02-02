<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // Cek dulu: Kalau kolom 'no_hp' BELUM ada, baru dibuat
        if (!Schema::hasColumn('users', 'no_hp')) {
            $table->string('no_hp')->nullable()->after('password');
        }
        
        // Cek dulu: Kalau kolom 'ktp_image' BELUM ada, baru dibuat
        if (!Schema::hasColumn('users', 'ktp_image')) {
            $table->string('ktp_image')->nullable()->after('no_hp'); // atau after password kalau no_hp sdh ada
        }
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['no_hp', 'ktp_image']);
    });
}
};
