<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pdrb_final', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sektor_id')
                ->constrained('commodities')
                ->onDelete('cascade');

            $table->year('tahun');

            $table->foreignId('triwulan_id')
                ->constrained('triwulanan')
                ->onDelete('cascade');

            $table->decimal('adhb', 20, 2)->nullable();
            $table->decimal('adhk', 20, 2)->nullable();

            $table->decimal('kontribusi', 10, 4)->nullable();
            $table->decimal('pertumbuhan', 10, 4)->nullable();

            $table->integer('versi')->default(1);
            $table->enum('status', ['draft', 'final'])->default('final');

            $table->unsignedBigInteger('finalized_by')->nullable();

            $table->unique(['sektor_id', 'tahun', 'triwulan_id', 'versi'], 'pdrb_final_unique');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pdrb_final');
    }
};