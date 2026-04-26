<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdrbFinal extends Model
{
    protected $table = 'pdrb_final';

    protected $fillable = [
        'tahun',
        'triwulan_id',
        'sektor_id',
        'adhb',
        'adhk',
        'kontribusi',
        'pertumbuhan',
        'versi',
        'status',
        'finalized_by',
    ];

    public function sektor()
    {
        return $this->belongsTo(Commodity::class, 'sektor_id');
    }
    public function triwulan()
    { 
        return $this->belongsTo(Triwulan::class); 
    }
    public function finalizer()
    { 
        return $this->belongsTo(User::class, 'finalized_by'); 
    }
}