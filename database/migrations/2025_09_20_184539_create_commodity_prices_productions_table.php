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

            $table->foreignId('indicator_id')
                ->nullable()
                ->constrained('indicators')
                ->onDelete('cascade');

            $table->foreignId('unit_price_id')
                ->nullable()
                ->constrained('units_harga')
                ->onDelete('cascade');

            $table->decimal('harga', 15, 2)->nullable();

            $table->foreignId('unit_production_id')
                ->nullable()
                ->constrained('units_produksi')
                ->onDelete('cascade');

            $table->decimal('produksi', 15, 2)->nullable();

            // Tahun & Timestamp
            $table->year('tahun');
            $table->timestamps();

            // Unique gabungan
            $table->unique(
                [
                    'commodity_id',
                    'indicator_id',
                    'unit_price_id',
                    'unit_production_id',
                    'tahun'
                ],
                'unique_price_production'
            );
        });
    }

    public function down()
    {
        Schema::dropIfExists('commodity_prices_productions');
    }
};
