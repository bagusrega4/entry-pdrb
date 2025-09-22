<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->string('indikator');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('indicators');
    }
};
