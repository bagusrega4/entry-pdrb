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
            'Rp/000 Ekor',
            'Rp/Rit (7 M3)',
            'Rp/Buah',
            'Rp/Pak',
            'Rp/Bal',
            'Rp/Bungkus',
            'Rp/Pcs',
            'Rp/Pasang',
            'Rp/Lembar',
            'Rp/Unit',
            'Rp/Sachset',
            'Rp/M2',
            'Rp/000 KWh',
            'Rp/Proyek',
            'Rp/IMB',
            'Rp/Kendaraan',
            'Rupiah',
            'Rp/Orang',
            'Rp/Rit (3 M3)',
            'Rp/Paket',
            'Rp/Siswa',
            'Rp/Pasien',
            'Rp/Tiket',
        ];

        foreach ($data as $satuan) {
            UnitHarga::create(['satuan_harga' => $satuan]);
        }
    }
}
