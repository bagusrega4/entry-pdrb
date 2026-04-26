<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulasi_riwayat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_simulasi');
            $table->unsignedBigInteger('dataset_id')->nullable();
            $table->longText('summary');
            $table->longText('hasil');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('dataset_id')
                  ->references('id')
                  ->on('io_datasets')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulasi_riwayat');
    }
};