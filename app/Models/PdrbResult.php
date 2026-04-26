<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdrbResult extends Model
{
    use HasFactory;

    protected $table = 'pdrb_results';

    protected $fillable = [
        'sektor_id',
        'tahun',
        'triwulan_id',
        'output',
        'ntb',
        'adhb',
        'adhk',
        'kontribusi',
        'pertumbuhan',
    ];

    public function sektor()
    {
        return $this->belongsTo(Commodity::class, 'sektor_id');
    }
}