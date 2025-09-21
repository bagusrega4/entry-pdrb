<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commodity extends Model
{
    protected $fillable = ['parent_id', 'kode', 'nama', 'level'];

    public function parent()
    {
        return $this->belongsTo(Commodity::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Commodity::class, 'parent_id');
    }
}
