<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommodityIhp extends Model
{
    protected $table = 'commodity_ihp';

    protected $fillable = [
        'commodity_id',
        'tahun',
        'triwulan_id',
        'ihp',
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
