<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::create('download_column_configs', function (Blueprint $table) {
            $table->id();
            $table->string('column_key')->unique();
            $table->string('column_label');
            $table->boolean('is_visible')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_mandatory')->default(false);
            $table->timestamps();
        });

        // Seed data default
        DB::table('download_column_configs')->insert([
            ['column_key' => 'kategori',               'column_label' => 'Kategori',               'is_visible' => true,  'sort_order' => 1,  'is_mandatory' => true],
            ['column_key' => 'sub_kategori_1',          'column_label' => 'Sub Kategori 1',          'is_visible' => true,  'sort_order' => 2,  'is_mandatory' => true],
            ['column_key' => 'sub_kategori_2',          'column_label' => 'Sub Kategori 2',          'is_visible' => true,  'sort_order' => 3,  'is_mandatory' => true],
            ['column_key' => 'sub_kategori_3',          'column_label' => 'Sub Kategori 3',          'is_visible' => true,  'sort_order' => 4,  'is_mandatory' => true],
            ['column_key' => 'nama_komoditas',          'column_label' => 'Nama Komoditas',          'is_visible' => true,  'sort_order' => 5,  'is_mandatory' => true],
            ['column_key' => 'indikator',               'column_label' => 'Indikator',               'is_visible' => true,  'sort_order' => 6,  'is_mandatory' => false],
            ['column_key' => 'satuan_produksi',         'column_label' => 'Satuan Produksi',         'is_visible' => true,  'sort_order' => 7,  'is_mandatory' => false],
            ['column_key' => 'satuan_harga',            'column_label' => 'Satuan Harga',            'is_visible' => false, 'sort_order' => 8,  'is_mandatory' => false],
            ['column_key' => 'satuan_luas',             'column_label' => 'Satuan Luas',             'is_visible' => false, 'sort_order' => 9,  'is_mandatory' => false],
            ['column_key' => 'satuan_biaya_perawatan',  'column_label' => 'Satuan Biaya Perawatan',  'is_visible' => false, 'sort_order' => 10, 'is_mandatory' => false],
            ['column_key' => 'produksi',                'column_label' => 'Produksi',                'is_visible' => true,  'sort_order' => 11, 'is_mandatory' => false],
            ['column_key' => 'harga',                   'column_label' => 'Harga',                   'is_visible' => true,  'sort_order' => 12, 'is_mandatory' => false],
            ['column_key' => 'rasio_output_ikutan',     'column_label' => 'Rasio Output Ikutan',     'is_visible' => false, 'sort_order' => 13, 'is_mandatory' => false],
            ['column_key' => 'rasio_wip_cbr',           'column_label' => 'Rasio WIP/CBR',           'is_visible' => false, 'sort_order' => 14, 'is_mandatory' => false],
            ['column_key' => 'rasio_biaya_antara',      'column_label' => 'Rasio Biaya Antara',      'is_visible' => false, 'sort_order' => 15, 'is_mandatory' => false],
            ['column_key' => 'ihp',                     'column_label' => 'IHP',                     'is_visible' => false, 'sort_order' => 16, 'is_mandatory' => false],
            ['column_key' => 'luas_tanam',              'column_label' => 'Luas Tanam',              'is_visible' => false, 'sort_order' => 17, 'is_mandatory' => false],
            ['column_key' => 'biaya_perawatan',         'column_label' => 'Biaya Perawatan',         'is_visible' => false, 'sort_order' => 18, 'is_mandatory' => false],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('download_column_configs');
    }
};