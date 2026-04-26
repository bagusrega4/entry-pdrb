<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('io_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dataset_id')
                  ->constrained('io_datasets')
                  ->onDelete('cascade');

            $table->integer('sektor');
            $table->double('nilai')->default(0);

            $table->timestamps();

            // satu sektor hanya satu output per dataset
            $table->unique(['dataset_id', 'sektor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('io_outputs');
    }
};