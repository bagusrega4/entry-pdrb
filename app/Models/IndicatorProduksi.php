<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorProduksi extends Model
{
    use HasFactory;

    protected $table = 'indicators_produksi';

    protected $fillable = [
        'indikator_produksi',
    ];

    public function commodityPrice()
    {
        return $this->hasMany(CommodityProduction::class);
    }
}
