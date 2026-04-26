<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Commodity;
use App\Exports\Sheets\PriceProductionSheet;

class PriceProductionExport implements WithMultipleSheets
{
    protected $rootCommodities;

    public function __construct($rootCommodities)
    {
        $this->rootCommodities = $rootCommodities;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->rootCommodities as $root) {
            $leafs = $this->collectLeaf($root);

            $sheets[] = new PriceProductionSheet($leafs, $root->nama);
        }

        return $sheets;
    }

    private function collectLeaf($commodity)
    {
        $result = collect();

        if ($commodity->children->count() == 0) {
            $result->push($commodity);
        } else {
            foreach ($commodity->children as $child) {
                $result = $result->merge($this->collectLeaf($child));
            }
        }

        return $result;
    }
}
