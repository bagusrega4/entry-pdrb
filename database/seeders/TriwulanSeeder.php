<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Triwulan;

class TriwulanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Triwulan I',
            'Triwulan II',
            'Triwulan III',
            'Triwulan IV',
        ];

        foreach ($data as $triwulan) {
            Triwulan::create(['triwulan' => $triwulan]);
        }
    }
}
