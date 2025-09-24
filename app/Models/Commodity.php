<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commodity extends Model
{
    protected $fillable = [
        'parent_id',
        'kode',
        'nama',
        'level',
        'indikator_id',
        'satuan_harga_id',
        'satuan_produksi_id'
    ];

    public function parent()
    {
        return $this->belongsTo(Commodity::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Commodity::class, 'parent_id');
    }

    public function pricesProduction()
    {
        return $this->hasMany(CommodityPriceProduction::class, 'commodity_id');
    }

    public function rasio()
    {
        return $this->hasMany(CommodityRasio::class, 'commodity_id');
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

    public function childrenRecursive()
    {
        return $this->children()->with([
            'childrenRecursive',
            'pricesProduction',
            'rasio',
            'indicator',
            'unitHarga',
            'unitProduksi'
        ]);
    }
}
