<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitHarga;
use App\Models\UnitProduksi;

class UnitProduksiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Ton',
            'Tangkai',
            'Pohon',
            'Rumpun',
            'Kg',
            'Ekor',
            'Liter',
            'M3',
            'Ekor',
            '000 Ekor',
        ];

        foreach ($data as $satuan) {
            UnitProduksi::create(['satuan_produksi' => $satuan]);
        }
    }
}
