<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitPerawatan extends Model
{
    use HasFactory;

    protected $table = 'units_perawatan';

    protected $fillable = [
        'satuan_biaya_perawatan',
    ];

    public function commodityWipCbr()
    {
        return $this->hasMany(CommodityWipCbr::class);
    }
}
