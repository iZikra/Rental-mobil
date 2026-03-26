<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('rentals', function (Blueprint $table) {
        $table->text('syarat_ketentuan')->nullable()->after('alamat');
    });
}

public function down()
{
    Schema::table('rentals', function (Blueprint $table) {
        $table->dropColumn('syarat_ketentuan');
    });
}
};
