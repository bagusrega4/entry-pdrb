<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tim extends Model
{
    protected $table = 'tims';
    protected $fillable = ['nama_tim'];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
    public function forms()
    {
        return $this->hasMany(Form::class, 'tim_id');
    }
}
