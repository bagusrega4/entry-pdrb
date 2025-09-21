<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('commodities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('commodities')->onDelete('cascade');
            $table->string('kode');
            $table->string('nama');
            $table->unsignedTinyInteger('level');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commodities');
    }
};
