<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nip_lama',
        'username',
        'password',
        'email',
        'id_role',   // role pengguna
        'tim_id',    // tim pengguna
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke tabel Pegawai
    public function nipLama()
    {
        return $this->belongsTo(Pegawai::class, 'nip_lama', 'nip_lama');
    }

    // Relasi ke tabel Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id');
    }

    // Relasi ke Tim
    public function tim()
    {
        return $this->belongsTo(Tim::class, 'tim_id', 'id');
    }

    // Relasi ke Form
    public function forms()
    {
        return $this->hasMany(Form::class, 'user_id', 'id');
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'nip_lama', 'nip_lama');
    }
}
