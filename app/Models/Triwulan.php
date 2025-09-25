<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Triwulan extends Model
{
    use HasFactory;

    protected $table = 'triwulanan';

    protected $fillable = [
        'triwulan',
    ];

    public function commodityPriceProduction()
    {
        return $this->hasMany(CommodityPriceProduction::class);
    }

    public function commodityRasio()
    {
        return $this->hasMany(CommodityRasio::class);
    }

    public function commodityIhp()
    {
        return $this->hasMany(CommodityIhp::class);
    }
}
