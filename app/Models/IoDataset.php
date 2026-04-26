<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IoDataset extends Model
{
    use HasFactory;

    protected $fillable = ['nama_dataset', 'tahun', 'jumlah_sektor'];

    public function transactions()
    {
        return $this->hasMany(IoTransaction::class, 'dataset_id');
    }

    public function finalDemands()
    {
        return $this->hasMany(IoFinalDemand::class, 'dataset_id');
    }

    public function outputs()
    {
        return $this->hasMany(IoOutput::class, 'dataset_id');
    }
}
