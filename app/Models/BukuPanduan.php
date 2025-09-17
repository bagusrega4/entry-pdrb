<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuPanduan extends Model
{
    use HasFactory;
    protected $table = 'file_buku_panduan';

    protected $fillable = [       
        'file',   
    ];
}
