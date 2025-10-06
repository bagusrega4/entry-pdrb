<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
            '000 Ekor',
            'Rit (7 M3)',
            'Buah',
            'Pak',
            'Bal',
            'Bungkus',
            'Pcs',
            'Pasang',
            'Lembar',
            'Unit',
            'Sachset',
            'M2',
            '000 KWh',
            'Proyek',
            'IMB',
            'Kendaraan',
            'Rupiah',
            'Orang',
            'Rit (3 M3)',
            'Paket',
            'Siswa',
            'Pasien',
            'Tiket',
        ];

        foreach ($data as $satuan) {
            UnitProduksi::create(['satuan_produksi' => $satuan]);
        }
    }
}
