<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommodityPrice extends Model
{
    protected $table = 'commodity_prices';
    protected $fillable = [
        'commodity_id',
        'indicator_price_id',
        'unit_price_id',
        'tahun',
        'harga',
    ];

    public function commodity()
    {
        return $this->belongsTo(Commodity::class, 'commodity_id');
    }

    public function indicatorHarga()
    {
        return $this->belongsTo(IndicatorHarga::class, 'indicator_price_id');
    }

    public function unitHarga()
    {
        return $this->belongsTo(UnitHarga::class, 'unit_price_id');
    }
}
