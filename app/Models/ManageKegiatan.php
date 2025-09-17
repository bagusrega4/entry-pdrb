<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageKegiatan extends Model
{
    use HasFactory;

    protected $table = 'manage_kegiatan'; // tabel detail kegiatan

    protected $fillable = [
        'kegiatan_id',
        'tim_id',
        'nama_kegiatan',
        'periode_mulai',
        'periode_selesai',
        'deskripsi',
        'status',
    ];

    // Relasi balik ke master kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
    
    public function tim()
    {
        return $this->belongsTo(Tim::class, 'tim_id');
    }
}
