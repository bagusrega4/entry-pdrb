<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IndicatorHarga;

class IndicatorHargaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'GKG',
            'Pipilan Kering Panen',
            'Umbi Segar/Umbi kering panen',
            'Umbi Segar',
            'Biji Kering',
            'Daun Segar',
            'Buah Segar',
            'Bunga Segar',
            'Pohon',
            'Tebu Giling',
            'Kapas Berbiji',
            'Serat Basah',
            'Rimpang Kering',
            'Lateks',
            'Tandan buah segar',
            'Buah Kelapa',
            'Mentor Basah',
            'Kulit Basah',
            'Polong Basah',
            'Buah Basah',
            'Gula Merah',
            'Nira',
            'Daun Basah',
            'Kulit Kering',
            'Biji Basah',
            'Biji',
            'Serat Kering',
            'Hewan Hidup',
            'Telur',
            'Ikan Segar',
            'Kayu Gelondongan',
            'Ikan Hidup',
        ];

        foreach ($data as $indikator) {
            IndicatorHarga::create(['indikator_harga' => $indikator]);
        }
    }
}
