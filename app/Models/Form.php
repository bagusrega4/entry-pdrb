<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $table = 'forms';

    protected $fillable = [
        'user_id',
        'tim_id',
        'kegiatan_id',
        'tanggal',
        'jam_mulai',
        'jam_akhir',
        'diketahui',
    ];

    public function managekegiatan()
    {
        return $this->belongsTo(ManageKegiatan::class, 'kegiatan_id', 'kegiatan_id');
    }

    public function tim()
    {
        return $this->belongsTo(Tim::class, 'tim_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
