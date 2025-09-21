<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitHarga extends Model
{
    use HasFactory;

    protected $table = 'units_harga';

    protected $fillable = [
        'satuan_harga',
    ];

    public function commodityPrice()
    {
        return $this->hasMany(CommodityPrice::class);
    }
}
