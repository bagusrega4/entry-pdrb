<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('io_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dataset_id')
                  ->constrained('io_datasets')
                  ->onDelete('cascade');

            $table->integer('baris');   // sektor asal
            $table->integer('kolom');   // sektor tujuan
            $table->double('nilai')->default(0);

            $table->timestamps();

            // biar tidak ada duplikasi sel matrix
            $table->unique(['dataset_id', 'baris', 'kolom']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('io_transactions');
    }
};