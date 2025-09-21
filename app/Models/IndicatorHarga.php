<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorHarga extends Model
{
    use HasFactory;

    protected $table = 'indicators_harga';

    protected $fillable = [
        'indikator_harga',
    ];

    public function commodityPrice()
    {
        return $this->hasMany(CommodityPrice::class);
    }
}
