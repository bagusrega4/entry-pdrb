<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IoOutput extends Model
{
    use HasFactory;

    protected $fillable = ['dataset_id', 'sektor', 'nilai'];

    public function dataset()
    {
        return $this->belongsTo(IoDataset::class, 'dataset_id');
    }

    protected $casts = [
        'nilai' => 'float'
    ];
}
