<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'nama',
        'tahun',
        'triwulan_id',
        'jenis',
        'keterangan',
        'file_path',
        'file_type',
        'file_size',
        'modul',
        'commodity_id',
        'uploaded_by',
        'version'
    ];

    protected $casts = [
        'tahun' => 'integer',
        'triwulan_id' => 'integer',
        'commodity_id' => 'integer',
        'uploaded_by' => 'integer',
        'file_size' => 'integer',
        'version' => 'integer',
    ];

    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
    }

    public function triwulan()
    {
        return $this->belongsTo(Triwulan::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) return '-';

        $size = $this->file_size;

        if ($size < 1024) return $size . ' B';
        if ($size < 1024 * 1024) return round($size / 1024, 2) . ' KB';
        return round($size / (1024 * 1024), 2) . ' MB';
    }

    public function getModulLabelAttribute()
    {
        return match ($this->modul) {
            'harga_produksi' => 'Harga dan Produksi',
            'rasio' => 'Rasio',
            'ihp' => 'IHP',
            'wip_cbr' => 'WIP/CBR',
            default => ucfirst($this->modul),
        };
    }

    public function scopeByModul($query, $modul)
    {
        return $query->where('modul', $modul);
    }

    public function scopeByCommodity($query, $commodityId)
    {
        return $query->where('commodity_id', $commodityId);
    }

    public function scopeByYear($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }
}