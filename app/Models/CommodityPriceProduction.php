<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommodityPriceProduction extends Model
{
    protected $table = 'commodity_prices_productions';
    protected $fillable = [
        'commodity_id',
        'indicator_id',
        'unit_price_id',
        'harga',
        'unit_production_id',
        'produksi',
        'tahun',
    ];

    public function commodity()
    {
        return $this->belongsTo(Commodity::class, 'commodity_id');
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class, 'indicator_id');
    }

    public function unitHarga()
    {
        return $this->belongsTo(UnitHarga::class, 'unit_harga_id');
    }

    public function unitProduksi()
    {
        return $this->belongsTo(UnitProduksi::class, 'unit_produksi_id');
    }
}
