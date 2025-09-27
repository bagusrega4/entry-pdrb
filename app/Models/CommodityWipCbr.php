<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommodityWipCbr extends Model
{
    protected $table = 'commodity_wip_cbr';

    protected $fillable = [
        'commodity_id',
        'tahun',
        'triwulan_id',
        'luas_tanam_akhir_tahun',
        'biaya_perawatan',
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
