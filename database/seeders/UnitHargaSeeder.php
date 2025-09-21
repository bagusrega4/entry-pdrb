<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitHarga;

class UnitHargaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Rp/Ton',
            'Rp/Tangkai',
            'Rp/Pohon',
            'Rp/Rumpun',
            'Rp/Kg',
            'Rp/Ekor',
            'Rp/Liter',
            'Rp/M3',
            'Rp/Ekor',
            'Rp/000 Ekor',
        ];

        foreach ($data as $satuan) {
            UnitHarga::create(['satuan_harga' => $satuan]);
        }
    }
}
