<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitLuas extends Model
{
    use HasFactory;

    protected $table = 'units_luas';

    protected $fillable = [
        'satuan_luas_tanam',
    ];

    public function commodityWipCbr()
    {
        return $this->hasMany(CommodityWipCbr::class);
    }
}
