<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommodityIhp extends Model
{
    protected $table = 'commodity_ihp';

    protected $fillable = [
        'commodity_id',
        'tahun',
        'ihp',
    ];

    public function commodity()
    {
        return $this->belongsTo(Commodity::class, 'commodity_id');
    }
}
