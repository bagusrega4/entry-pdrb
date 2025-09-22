<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('commodities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('commodities')
                ->onDelete('cascade');
            $table->string('kode')->index();
            $table->string('nama');
            $table->unsignedTinyInteger('level');

            $table->string('indikator_id')->nullable();
            $table->string('satuan_harga_id')->nullable();
            $table->string('satuan_produksi_id')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commodities');
    }
};
