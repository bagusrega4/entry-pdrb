<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('indicators_harga', function (Blueprint $table) {
            $table->id();
            $table->string('indikator_harga');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('indicators_harga');
    }
};
