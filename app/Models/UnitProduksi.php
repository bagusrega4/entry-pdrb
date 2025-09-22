<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitProduksi extends Model
{
    use HasFactory;

    protected $table = 'units_produksi';

    protected $fillable = [
        'satuan_produksi',
    ];

    public function commodityPriceProduction()
    {
        return $this->hasMany(CommodityPriceProduction::class);
    }
}
