<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('rentals', function (Blueprint $table) {
        $table->string('nama_bank')->nullable()->after('alamat');
        $table->string('no_rekening')->nullable()->after('nama_bank');
        $table->string('atas_nama_rekening')->nullable()->after('no_rekening');
    });
}

public function down()
{
    Schema::table('rentals', function (Blueprint $table) {
        $table->dropColumn(['nama_bank', 'no_rekening', 'atas_nama_rekening']);
    });
}
};
