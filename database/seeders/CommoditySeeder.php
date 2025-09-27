<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commodity;

class CommoditySeeder extends Seeder
{
    public function run()
    {
        // Load file JSON
        $json = file_get_contents(database_path('seeders/data/commodities.json'));
        $data = json_decode($json, true);

        // Rekursif insert
        $this->insertCommodities($data);
    }

    private function insertCommodities(array $items, $parentId = null, $parentCode = '', $level = 1)
    {
        foreach ($items as $index => $item) {
            // kalau tidak ada "kode", generate berdasarkan parent
            $kode = $item['kode'] ?? ($parentCode ? $parentCode . '.' . ($index + 1) : null);

            $commodity = Commodity::create([
                'parent_id'         => $parentId,
                'kode'              => $kode,
                'nama'              => $item['nama'],
                'level'             => $level,
                'indikator_id'      => $item['indikator_id'] ?? null,
                'satuan_harga_id'   => $item['satuan_harga_id'] ?? null,
                'satuan_produksi_id' => $item['satuan_produksi_id'] ?? null,
                'satuan_luas_tanam_id' => $item['satuan_luas_tanam_id'] ?? null,
                'satuan_biaya_perawatan_id' => $item['satuan_biaya_perawatan_id'] ?? null,
            ]);

            // kalau ada children, rekursif
            if (!empty($item['children'])) {
                $this->insertCommodities($item['children'], $commodity->id, $kode, $level + 1);
            }
        }
    }
}
