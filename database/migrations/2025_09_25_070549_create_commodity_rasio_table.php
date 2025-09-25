<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('commodity_rasio', function (Blueprint $table) {
            $table->id();

            $table->foreignId('commodity_id')
                ->constrained('commodities')
                ->onDelete('cascade');

            $table->year('tahun');

            $table->foreignId('triwulan_id')
                ->nullable()
                ->constrained('triwulanan')
                ->onDelete('set null');

            $table->decimal('rasio_output_ikutan', 30, 10)->nullable();

            $table->decimal('rasio_wip_cbr', 30, 10)->nullable();

            $table->decimal('rasio_biaya_antara', 30, 10)->nullable();

            $table->timestamps();

            $table->unique(
                ['commodity_id', 'tahun', 'triwulan_id'],
                'unique_rasio_per_year_triwulan'
            );
        });
    }

    public function down()
    {
        Schema::dropIfExists('commodity_rasio');
    }
};
