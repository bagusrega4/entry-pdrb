<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EconomicGroupSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('economic_group_commodities')->delete();
        DB::table('economic_groups')->delete();

        $groups = [
            [
                'key'    => 'primer',
                'label'  => 'Primer',
                'warna'  => '#22c55e',
                'urutan' => 1,
                'kodes'  => [1, 2],          // Pertanian, Pertambangan
            ],
            [
                'key'    => 'sekunder',
                'label'  => 'Sekunder',
                'warna'  => '#3b82f6',
                'urutan' => 2,
                'kodes'  => [3, 4, 5, 6],   // Industri, Listrik, Air, Konstruksi
            ],
            [
                'key'    => 'tersier',
                'label'  => 'Tersier',
                'warna'  => '#f97316',
                'urutan' => 3,
                'kodes'  => [7,8,9,10,11,12,13,14,15,16,17], // Jasa & perdagangan
            ],
        ];

        foreach ($groups as $group) {
            $id = DB::table('economic_groups')->insertGetId([
                'key'        => $group['key'],
                'label'      => $group['label'],
                'warna'      => $group['warna'],
                'urutan'     => $group['urutan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $pivotRows = array_map(fn($kode) => [
                'economic_group_id' => $id,
                'commodity_kode'    => $kode,
                'created_at'        => now(),
                'updated_at'        => now(),
            ], $group['kodes']);

            DB::table('economic_group_commodities')->insert($pivotRows);
        }
    }
}