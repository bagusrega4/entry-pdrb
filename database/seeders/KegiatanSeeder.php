<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class KegiatanSeeder extends Seeder
{
    public function run(): void
    {
        // Path file JSON
        $path = database_path('seeders/data/kegiatan.json');

        // Baca isi file JSON
        $json = File::get($path);

        // Ubah JSON menjadi array
        $data = json_decode($json, true);

        // Tambahkan timestamp otomatis
        $now = now();
        foreach ($data as &$item) {
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
        }

        // Insert ke tabel kegiatan
        DB::table('kegiatan')->insert($data);
    }
}
