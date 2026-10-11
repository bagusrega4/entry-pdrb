<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pdrb_final', function (Blueprint $table) {
            $table->text('komentar')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('pdrb_final', function (Blueprint $table) {
            $table->dropColumn('komentar');
        });
    }
};
