<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('commodity_productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commodity_id')
                ->constrained('commodities')
                ->onDelete('cascade');

            $table->foreignId('indicator_production_id')
                ->nullable()
                ->constrained('indicators_produksi')
                ->onDelete('cascade');

            $table->foreignId('unit_production_id')
                ->nullable()
                ->constrained('units_produksi')
                ->onDelete('cascade');

            $table->year('tahun');

            $table->decimal('produksi', 15, 2);

            $table->timestamps();

            $table->unique(
                ['commodity_id', 'indicator_production_id', 'unit_production_id', 'tahun'],
                'unique_production'
            );
        });
    }

    public function down()
    {
        Schema::dropIfExists('commodity_productions');
    }
};
