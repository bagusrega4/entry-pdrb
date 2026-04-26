<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('economic_groups', function (Blueprint $table) {
            $table->id();
            $table->string('key', 20)->unique()->comment('primer | sekunder | tersier');
            $table->string('label', 50);
            $table->string('warna', 20)->default('#9ca3af')->comment('hex color');
            $table->integer('urutan')->default(0)->comment('urutan tampil');
            $table->timestamps();
        });

        Schema::create('economic_group_commodities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('economic_group_id')
                  ->constrained('economic_groups')
                  ->cascadeOnDelete();
            $table->unsignedInteger('commodity_kode')->comment('kode dari tabel commodities');
            $table->unique(['economic_group_id', 'commodity_kode'], 'egc_group_kode_unique');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('economic_group_commodities');
        Schema::dropIfExists('economic_groups');
    }
};