<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();

            // Relasi ke user (pembuat form)
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Relasi ke tim
            $table->unsignedBigInteger('tim_id');
            $table->foreign('tim_id')->references('id')->on('tims')->onDelete('cascade');

            // Relasi ke kegiatan
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->cascadeOnDelete();

            // Data jadwal
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_akhir');
            $table->boolean('diketahui')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
