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

            // indikator, satuan harga, satuan produksi tetap nullable
            $table->foreignId('indikator_id')
                ->nullable()
                ->constrained('indicators')
                ->onDelete('cascade');

            $table->foreignId('satuan_harga_id')
                ->nullable()
                ->constrained('units_harga')
                ->onDelete('cascade');

            $table->year('tahun');

            $table->decimal('harga', 15, 2)->nullable();

            $table->foreignId('satuan_produksi_id')
                ->nullable()
                ->constrained('units_produksi')
                ->onDelete('cascade');

            $table->decimal('produksi', 15, 2)->nullable();

            $table->timestamps();

            $table->unique(
                ['commodity_id', 'tahun'],
                'unique_price_production_per_year'
            );
        });
    }

    public function down()
    {
        Schema::dropIfExists('commodity_prices_productions');
    }
};
