<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = ['tim_id', 'tanggal', 'jam'];

    protected $casts = [
        'jam' => 'array', // supaya otomatis array
    ];

    public function tim()
    {
        return $this->belongsTo(Tim::class);
    }
}
