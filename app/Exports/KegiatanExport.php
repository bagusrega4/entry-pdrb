<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class KegiatanExport implements FromCollection, WithHeadings
{
    protected $kegiatanList;

    public function __construct($kegiatanList)
    {
        $this->kegiatanList = $kegiatanList;
    }

    public function collection()
    {
        return $this->kegiatanList->map(function ($item, $key) {
            return [
                'No' => $key + 1,
                'Nama Kegiatan' => $item->nama_kegiatan,
                'Periode' => Carbon::parse($item->periode_mulai)->translatedFormat('d F Y') .
                    ' - ' .
                    Carbon::parse($item->periode_selesai)->translatedFormat('d F Y'),
                'Deskripsi' => $item->deskripsi,
                'Nama Tim' => $item->tim->nama_tim ?? '-',
                'Status' => ucfirst($item->status),
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Nama Kegiatan', 'Periode', 'Deskripsi', 'Nama Tim', 'Status'];
    }
}
