<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('commodity_prices_productions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('commodity_id')
                ->constrained('commodities')
                ->onDelete('cascade');

            $table->year('tahun');

            $table->foreignId('triwulan_id')
                ->nullable()
                ->constrained('triwulanan')
                ->onDelete('set null');

            $table->decimal('harga', 30, 10)->nullable();
            $table->decimal('produksi', 30, 10)->nullable();

            $table->timestamps();

            $table->unique(
                ['commodity_id', 'tahun', 'triwulan_id'],
                'unique_price_production_per_year_triwulan'
            );
        });
    }

    public function down()
    {
        Schema::dropIfExists('commodity_prices_productions');
    }
};
