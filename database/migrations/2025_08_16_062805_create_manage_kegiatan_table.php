<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('manage_kegiatan', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel kegiatan
            $table->unsignedBigInteger('kegiatan_id')->nullable();
            $table->foreign('kegiatan_id')
                ->references('id')
                ->on('kegiatan')
                ->onDelete('set null');

            // Relasi ke tabel tim
            $table->unsignedBigInteger('tim_id')->nullable();
            $table->foreign('tim_id')
                ->references('id')
                ->on('tims') // pastikan nama tabelnya benar (tims atau tim?)
                ->onDelete('set null');

            $table->string('nama_kegiatan');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manage_kegiatan');
    }
};
