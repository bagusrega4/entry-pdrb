<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommodityPriceProduction extends Model
{
    protected $table = 'commodity_prices_productions';

    protected $fillable = [
        'commodity_id',
        'harga',
        'triwulan_id',
        'produksi',
        'tahun',
    ];

    public function commodity()
    {
        return $this->belongsTo(Commodity::class, 'commodity_id');
    }

    public function triwulan()
    {
        return $this->belongsTo(Triwulan::class, 'triwulan_id');
    }
}
