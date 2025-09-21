<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('units_produksi', function (Blueprint $table) {
            $table->id();
            $table->string('satuan_produksi');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('units_produksi');
    }
};
