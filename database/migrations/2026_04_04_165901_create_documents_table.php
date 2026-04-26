<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->year('tahun')->nullable();

            $table->foreignId('triwulan_id')
                ->nullable()
                ->constrained('triwulanan')
                ->nullOnDelete();

            $table->enum('modul', [
                'harga_produksi',
                'rasio',
                'ihp',
                'wip_cbr'
            ]);

            $table->foreignId('commodity_id')
                ->nullable()
                ->constrained('commodities')
                ->nullOnDelete();

            $table->string('jenis')->nullable();
            $table->text('keterangan')->nullable();

            // file
            $table->text('file_path');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();

            // user
            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->integer('version')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};