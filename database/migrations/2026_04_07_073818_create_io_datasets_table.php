<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('io_datasets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dataset');
            $table->year('tahun');
            $table->integer('jumlah_sektor')->default(17);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('io_datasets');
    }
};