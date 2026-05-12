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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nip_lama', 20)->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('email')->unique();

            // Relasi ke tabel role
            $table->unsignedBigInteger('id_role');
            $table->foreign('id_role')->references('id')->on('role');

            // Relasi ke tabel tim (nullable jika belum punya tim)
            $table->unsignedBigInteger('tim_id')->nullable();
            $table->foreign('tim_id')->references('id')->on('tims');

            $table->rememberToken();
            $table->timestamps();

            // Relasi ke tabel pegawai
            $table->foreign('nip_lama')->references('nip_lama')->on('pegawai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
