<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('commodity_wip_cbr', function (Blueprint $table) {
            $table->id();

            $table->foreignId('commodity_id')
                ->constrained('commodities')
                ->onDelete('cascade');

            $table->year('tahun');

            $table->foreignId('triwulan_id')
                ->nullable()
                ->constrained('triwulanan')
                ->onDelete('set null');

            $table->decimal('luas_tanam_akhir_tahun', 30, 10)->nullable();
            $table->decimal('biaya_perawatan', 30, 10)->nullable();

            $table->timestamps();

            $table->unique(
                ['commodity_id', 'tahun', 'triwulan_id'],
                'unique_wip_cbr_per_year_triwulan'
            );
        });
    }

    public function down()
    {
        Schema::dropIfExists('commodity_wip_cbr');
    }
};
