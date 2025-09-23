<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommodityPriceProduction extends Model
{
    protected $table = 'commodity_prices_productions';

    protected $fillable = [
        'commodity_id',
        'indikator_id',
        'satuan_harga_id',
        'harga',
        'satuan_produksi_id',
        'produksi',
        'tahun',
    ];

    public function commodity()
    {
        return $this->belongsTo(Commodity::class, 'commodity_id');
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class, 'indikator_id');
    }

    public function unitHarga()
    {
        return $this->belongsTo(UnitHarga::class, 'satuan_harga_id');
    }

    public function unitProduksi()
    {
        return $this->belongsTo(UnitProduksi::class, 'satuan_produksi_id');
    }
}
