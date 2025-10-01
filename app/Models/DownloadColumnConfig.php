<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadColumnConfig extends Model
{
    protected $fillable = [
        'column_key',
        'column_label',
        'is_visible',
        'order',
        'is_mandatory'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'is_mandatory' => 'boolean',
    ];

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}