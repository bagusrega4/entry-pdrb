<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EconomicGroup extends Model
{
    protected $fillable = ['key', 'label', 'warna', 'urutan'];

    public function commodities(): BelongsToMany
    {
        return $this->belongsToMany(
            Commodity::class,
            'economic_group_commodities',
            'economic_group_id',
            'commodity_kode',   
            'id',               
            'kode'              
        );
    }

    public function getCommodityKodesAttribute(): array
    {
        return \DB::table('economic_group_commodities')
            ->where('economic_group_id', $this->id)
            ->pluck('commodity_kode')
            ->toArray();
    }
}