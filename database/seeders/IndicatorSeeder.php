<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Indicator;

class IndicatorSeeder extends Seeder
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
            'Bunga Basah',
            'Pohon',
            'Tebu Giling',
            'Kapas Berbiji',
            'Daun Kering',
            'Buah Kering',
            'Buah',
            'Serat Basah',
            'Rimpang Kering',
            'Lateks',
            'Tandan buah segar',
            'Buah Kelapa',
            'Bunga Kering',
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
            'Susu',
            'Ikan Segar',
            'Kayu Gelondongan',
            'Ikan Hidup',
        ];

        foreach ($data as $indikator) {
            Indicator::create(['indikator' => $indikator]);
        }
    }
}
