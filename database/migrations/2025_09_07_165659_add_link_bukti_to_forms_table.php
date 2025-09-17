<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->string('link_bukti')->nullable()->after('diketahui');
        });
    }

    public function down()
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->dropColumn('link_bukti');
        });
    }
};
