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

            $table->decimal('harga', 15, 2)->nullable();

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
