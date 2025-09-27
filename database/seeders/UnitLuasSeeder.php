<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitLuas;

class UnitLuasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Ha',
            'Ha (Pohon)',
            'Ton',
            'Ekor',
            'M3',
            '000 Ekor',
        ];

        foreach ($data as $satuan) {
            UnitLuas::create(['satuan_luas_tanam' => $satuan]);
        }
    }
}
