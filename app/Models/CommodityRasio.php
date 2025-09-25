<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommodityRasio extends Model
{
    protected $table = 'commodity_rasio';

    protected $fillable = [
        'commodity_id',
        'tahun',
        'triwulan_id',
        'rasio_output_ikutan',
        'rasio_wip_cbr',
        'rasio_biaya_antara',
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
