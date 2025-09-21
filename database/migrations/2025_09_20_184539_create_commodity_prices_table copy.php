<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('commodity_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commodity_id')
                ->constrained('commodities')
                ->onDelete('cascade');

            $table->foreignId('indicator_price_id')
                ->nullable()
                ->constrained('indicators_harga')
                ->onDelete('cascade');

            $table->foreignId('unit_price_id')
                ->nullable()
                ->constrained('units_harga')
                ->onDelete('cascade');

            $table->year('tahun');

            $table->decimal('harga', 15, 2);

            $table->timestamps();

            $table->unique(
                ['commodity_id', 'indicator_price_id', 'unit_price_id', 'tahun'],
                'unique_price'
            );
        });
    }

    public function down()
    {
        Schema::dropIfExists('commodity_prices');
    }
};
