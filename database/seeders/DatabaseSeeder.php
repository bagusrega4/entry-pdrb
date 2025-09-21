<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(PegawaiSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CommoditySeeder::class);
        $this->call(IndicatorHargaSeeder::class);
        $this->call(UnitHargaSeeder::class);
        $this->call(IndicatorProduksiSeeder::class);
        $this->call(UnitProduksiSeeder::class);
    }
}
