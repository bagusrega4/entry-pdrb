<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadColumnConfig extends Model
{
    protected $table = 'download_column_configs';

    protected $fillable = [
        'column_key',
        'column_label',
        'is_visible',
        'sort_order',
        'is_mandatory',
    ];

    protected $casts = [
        'is_visible'   => 'boolean',
        'is_mandatory' => 'boolean',
        'sort_order'   => 'integer',
    ];

    public function scopeVisible($query)
    {
        return $query->where(function ($q) {
            $q->where('is_visible', true)
              ->orWhere('is_mandatory', true);
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }
}