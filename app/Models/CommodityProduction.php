<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommodityProduction extends Model
{
    protected $table = 'commodity_productions';
    protected $fillable = [
        'commodity_id',
        'indicator_production_id',
        'unit_production_id',
        'tahun',
        'produksi',
    ];

    public function commodity()
    {
        return $this->belongsTo(Commodity::class, 'commodity_id');
    }

    public function indicatorProduksi()
    {
        return $this->belongsTo(IndicatorProduksi::class, 'indicator_production_id');
    }

    public function unitProduksi()
    {
        return $this->belongsTo(UnitProduksi::class, 'unit_production_id');
    }
}
