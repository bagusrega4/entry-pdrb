<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RasioSheet implements FromArray, WithHeadings, WithTitle
{
    protected $commodities;
    protected $title;

    public function __construct($commodities, $title)
    {
        $this->commodities = $commodities;
        $this->title = $title;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        return [
            'commodity_id',
            'kode_komoditas',
            'nama_komoditas',
            'indikator',
            'satuan_produksi',
            'tahun',
            'triwulan_id',
            'rasio_output_ikutan',
            'rasio_wip_cbr',
            'rasio_biaya_antara',
        ];
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->commodities as $c) {
            $rows[] = [
                $c->id,
                $c->kode,
                $c->nama,
                $c->indicator?->indikator ?? '',
                $c->unitProduksi?->satuan_produksi ?? '',
                '',
                '',
                '',
                '',
                '',
            ];
        }

        return $rows;
    }
}