<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitPerawatan;

class UnitPerawatanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Rp/Ha',
            'Rp/Tangkai',
            'Rp/Kg',
            'Rp/Pohon',
            'Rp/Ekor',
            'Rp/M3',
            'Rp/Ton',
        ];

        foreach ($data as $satuan) {
            UnitPerawatan::create(['satuan_biaya_perawatan' => $satuan]);
        }
    }
}
