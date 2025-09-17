<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan'; // tabel master kegiatan

    protected $fillable = [
        'nama_kegiatan',
    ];

    // Relasi ke manage_kegiatan (satu kegiatan bisa punya banyak detail)
    public function manageKegiatan()
    {
        return $this->hasMany(ManageKegiatan::class, 'kegiatan_id');
    }
}
