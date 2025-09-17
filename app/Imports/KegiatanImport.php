<?php

namespace App\Imports;

use App\Models\Kegiatan;
use App\Models\ManageKegiatan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Facades\Auth;

class KegiatanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip jika nama_kegiatan kosong
        if (!isset($row['nama_kegiatan']) || empty(trim($row['nama_kegiatan']))) {
            return null;
        }

        // 1. Simpan ke tabel kegiatan (hanya nama_kegiatan)
        $kegiatan = Kegiatan::create([
            'nama_kegiatan' => $row['nama_kegiatan'],
        ]);

        // 2. Ambil tim_id dari user login
        $timId = Auth::user()->tim_id ?? null;

        // 3. Simpan ke tabel manage_kegiatan
        return new ManageKegiatan([
            'kegiatan_id'     => $kegiatan->id,   // foreign key dari tabel kegiatan
            'tim_id'          => $timId,          // tim user yg login
            'nama_kegiatan'   => $row['nama_kegiatan'],
            'deskripsi'       => $row['deskripsi'] ?? null,
            'periode_mulai'   => $this->transformDate($row['periode_mulai']),
            'periode_selesai' => $this->transformDate($row['periode_selesai']),
            'status'          => 'aktif',
        ]);
    }

    private function transformDate($value, $format = 'Y-m-d')
    {
        try {
            if (is_numeric($value)) {
                return \Carbon\Carbon::instance(
                    ExcelDate::excelToDateTimeObject($value)
                )->format($format);
            } else {
                return \Carbon\Carbon::parse($value)->format($format);
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}
