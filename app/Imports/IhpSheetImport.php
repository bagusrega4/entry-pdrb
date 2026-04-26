<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use App\Models\Commodity;
use App\Models\CommodityIhp;
use App\Models\Triwulan;

class IhpSheetImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private IhpImport $parent;

    private array $commodityCache = [];
    private array $triwulanCache  = [];

    public function __construct(IhpImport $parent)
    {
        $this->parent = $parent;
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $commodityId = $this->cleanInt($row['commodity_id'] ?? null);
            $kode        = trim($row['kode_komoditas']  ?? '');
            $tahun       = $this->cleanInt($row['tahun']         ?? null);
            $triwulanId  = $this->cleanInt($row['triwulan_id']   ?? null);
            $ihp       = $this->cleanNumber($row['ihp']      ?? null);

            if ($ihp === null) {
                $this->parent->skipped++;
                continue;
            }

            if (!$tahun || $tahun < 1900 || $tahun > 2100) {
                $this->parent->errors[] = "Baris {$rowNumber}: Tahun tidak valid ({$tahun}).";
                $this->parent->skipped++;
                continue;
            }

            $commodity = $this->resolveCommodity($commodityId, $kode);

            if (!$commodity) {
                $this->parent->errors[] = "Baris {$rowNumber}: Komoditas tidak ditemukan "
                    . "(commodity_id={$commodityId}, kode={$kode}).";
                $this->parent->skipped++;
                continue;
            }

            $resolvedTriwulanId = null;

            if ($triwulanId !== null) {
                $triwulan = $this->resolveTriwulan($triwulanId);

                if (!$triwulan) {
                    $this->parent->errors[] = "Baris {$rowNumber}: triwulan_id={$triwulanId} tidak ditemukan.";
                    $this->parent->skipped++;
                    continue;
                }

                $resolvedTriwulanId = $triwulan->id;
            }

            try {
                CommodityIhp::updateOrCreate(
                    [
                        'commodity_id' => $commodity->id,
                        'tahun'        => $tahun,
                        'triwulan_id'  => $resolvedTriwulanId,
                    ],
                    [
                        'ihp'    => $ihp,
                    ]
                );

                $this->parent->imported++;

            } catch (\Throwable $e) {
                $this->parent->errors[] = "Baris {$rowNumber}: Gagal simpan — " . $e->getMessage();
                $this->parent->skipped++;
            }
        }
    }

    private function resolveCommodity(?int $id, string $kode): ?Commodity
    {
        if ($id) {
            if (!isset($this->commodityCache['id_' . $id])) {
                $this->commodityCache['id_' . $id] = Commodity::find($id);
            }
            if ($this->commodityCache['id_' . $id]) {
                return $this->commodityCache['id_' . $id];
            }
        }

        if ($kode !== '') {
            if (!isset($this->commodityCache['kode_' . $kode])) {
                $this->commodityCache['kode_' . $kode] = Commodity::where('kode', $kode)->first();
            }
            return $this->commodityCache['kode_' . $kode];
        }

        return null;
    }

    private function resolveTriwulan(int $id): ?Triwulan
    {
        if (!isset($this->triwulanCache[$id])) {
            $this->triwulanCache[$id] = Triwulan::find($id);
        }
        return $this->triwulanCache[$id];
    }

    private function cleanNumber(mixed $value): ?float
    {
        if ($value === null || $value === '') return null;

        $s = (string) $value;
        $s = trim(str_replace(['Rp', ' '], '', $s));

        $hasDot   = str_contains($s, '.');
        $hasComma = str_contains($s, ',');

        if ($hasDot && $hasComma) {
            if (strrpos($s, ',') > strrpos($s, '.')) {
                $s = str_replace('.', '', $s);
                $s = str_replace(',', '.', $s);
            } else {
                $s = str_replace(',', '', $s);
            }
        } elseif ($hasComma) {
            $afterComma = substr($s, strrpos($s, ',') + 1);
            $s = (strlen($afterComma) <= 2)
                ? str_replace(',', '.', $s)
                : str_replace(',', '', $s);
        } elseif ($hasDot) {
            $afterDot = substr($s, strrpos($s, '.') + 1);
            $dotCount = substr_count($s, '.');
            if ($dotCount > 1 || strlen($afterDot) === 3) {
                $s = str_replace('.', '', $s);
            }
        }

        $s = preg_replace('/[^0-9.\-]/', '', $s);
        $num = (float) $s;

        return is_nan($num) ? null : $num;
    }

    private function cleanInt(mixed $value): ?int
    {
        if ($value === null || $value === '') return null;
        $v = (int) $value;
        return $v === 0 ? null : $v;
    }
}