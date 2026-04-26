<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commodity;
use App\Models\CommodityPriceProduction;
use App\Models\CommodityRasio;
use App\Models\CommodityIhp;
use App\Models\CommodityWipCbr;
use App\Models\PdrbResult;

class PdrbController extends Controller
{
    private const KONVERSI_KE_MILIAR = 1_000_000_000;

    public function index()
    {
        $tahun       = request('tahun');
        $triwulan_id = request('triwulan_id');

        $jumlahSektor = Commodity::whereNull('parent_id')->count();

        $mapToSektor = function ($collection) {
            return $collection->map(function ($item) {
                if (!$item->commodity) return null;
                $commodity = $item->commodity;
                while ($commodity->parent_id !== null) {
                    $commodity = $commodity->parent;
                }
                return $commodity->id;
            })->filter()->unique()->count();
        };

        $status = [
            'hp' => ($tahun && $triwulan_id)
                ? $mapToSektor(
                    CommodityPriceProduction::with('commodity.parent.parent.parent')
                        ->where('tahun', $tahun)
                        ->where('triwulan_id', $triwulan_id)
                        ->get()
                ) >= $jumlahSektor
                : false,

            'rasio' => ($tahun && $triwulan_id)
                ? $mapToSektor(
                    CommodityRasio::with('commodity.parent.parent.parent')
                        ->where('tahun', $tahun)
                        ->where('triwulan_id', $triwulan_id)
                        ->get()
                ) >= $jumlahSektor
                : false,

            'ihp' => ($tahun && $triwulan_id)
                ? $mapToSektor(
                    CommodityIhp::with('commodity.parent.parent.parent')
                        ->where('tahun', $tahun)
                        ->where('triwulan_id', $triwulan_id)
                        ->get()
                ) >= $jumlahSektor
                : false,

            'wip' => ($tahun && $triwulan_id)
                ? $mapToSektor(
                    CommodityWipCbr::with('commodity.parent.parent.parent')
                        ->where('tahun', $tahun)
                        ->where('triwulan_id', $triwulan_id)
                        ->get()
                ) >= $jumlahSektor
                : false,
        ];

        $tahunList = CommodityPriceProduction::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $triwulans = \App\Models\Triwulan::all();

        $results = PdrbResult::with('sektor')
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->when($triwulan_id, fn($q) => $q->where('triwulan_id', $triwulan_id))
            ->get();

        // Nilai sudah dalam Miliar Rp (disimpan dengan konversi di ::hitung())
        $totalAdhb   = $results->sum('adhb');
        $totalAdhk   = $results->sum('adhk');
        $totalNtb    = $results->sum('ntb');
        $totalOutput = $results->sum('output');

        $totalQoq = null;
        $totalYoy = null;

        $pertumbuhan    = collect();
        $pertumbuhanQoq = collect();

        if ($tahun && $triwulan_id) {
            $resultsTahunLalu = PdrbResult::where('tahun', (int)$tahun - 1)
                ->where('triwulan_id', $triwulan_id)
                ->get()
                ->keyBy('sektor_id');

            $currentTriwulan = (int)$triwulan_id;

            if ($currentTriwulan == 1) {
                $tahunQoq    = (int)$tahun - 1;
                $triwulanQoq = 4;
            } else {
                $tahunQoq    = (int)$tahun;
                $triwulanQoq = $currentTriwulan - 1;
            }

            $dataQoq = PdrbResult::where('tahun', $tahunQoq)
                ->where('triwulan_id', $triwulanQoq)
                ->get()
                ->keyBy('sektor_id');

            foreach ($results as $row) {
                $adhkLalu = $resultsTahunLalu->get($row->sektor_id)?->adhk ?? null;
                $adhkQoq  = $dataQoq->get($row->sektor_id)?->adhk ?? null;

                $pertumbuhan[$row->sektor_id] = ($adhkLalu && $adhkLalu > 0)
                    ? (($row->adhk - $adhkLalu) / $adhkLalu) * 100
                    : null;

                $pertumbuhanQoq[$row->sektor_id] = ($adhkQoq && $adhkQoq > 0)
                    ? (($row->adhk - $adhkQoq) / $adhkQoq) * 100
                    : null;
            }

            // Total QoQ
            $prevTriwulan = (int)$triwulan_id - 1;
            $prevTahunQoq = (int)$tahun;
            if ($prevTriwulan < 1) {
                $prevTriwulan = 4;
                $prevTahunQoq = (int)$tahun - 1;
            }
            $totalAdhkPrevQoq = PdrbResult::where('tahun', $prevTahunQoq)
                ->where('triwulan_id', $prevTriwulan)
                ->sum('adhk');
            if ($totalAdhkPrevQoq > 0) {
                $totalQoq = (($totalAdhk - $totalAdhkPrevQoq) / $totalAdhkPrevQoq) * 100;
            }

            // Total YoY
            $totalAdhkPrevYoy = PdrbResult::where('tahun', (int)$tahun - 1)
                ->where('triwulan_id', $triwulan_id)
                ->sum('adhk');
            if ($totalAdhkPrevYoy > 0) {
                $totalYoy = (($totalAdhk - $totalAdhkPrevYoy) / $totalAdhkPrevYoy) * 100;
            }
        }

        return view('pdrb.index', compact(
            'status',
            'results',
            'tahunList',
            'triwulans',
            'totalAdhb',
            'totalAdhk',
            'totalNtb',
            'totalOutput',
            'pertumbuhan',
            'pertumbuhanQoq',
            'totalQoq',
            'totalYoy',
        ));
    }

    private function getDescendantIds(int $parentId): array
    {
        $ids      = [];
        $children = Commodity::where('parent_id', $parentId)->pluck('id')->toArray();
        foreach ($children as $childId) {
            $ids[] = $childId;
            $ids   = array_merge($ids, $this->getDescendantIds($childId));
        }
        return $ids;
    }

    public function hitung(Request $request)
    {
        $request->validate([
            'tahun'       => 'required|integer|min:2000|max:2100',
            'triwulan_id' => 'required|integer|exists:triwulanan,id',
        ]);

        $tahun       = (int) $request->tahun;
        $triwulan_id = (int) $request->triwulan_id;

        $jumlahSektor = Commodity::whereNull('parent_id')->count();

        $mapToSektor = function ($collection) {
            return $collection->map(function ($item) {
                if (!$item->commodity) return null;
                $commodity = $item->commodity;
                while ($commodity->parent_id !== null) {
                    $commodity = $commodity->parent;
                }
                return $commodity->id;
            })->filter()->unique()->count();
        };

        $jumlahHP = $mapToSektor(
            CommodityPriceProduction::with('commodity.parent.parent.parent')
                ->where('tahun', $tahun)
                ->where('triwulan_id', $triwulan_id)
                ->get()
        );

        $jumlahRasio = $mapToSektor(
            CommodityRasio::with('commodity.parent.parent.parent')
                ->where('tahun', $tahun)
                ->where('triwulan_id', $triwulan_id)
                ->get()
        );

        if ($jumlahHP < $jumlahSektor || $jumlahRasio < $jumlahSektor) {
            return back()->with('error', 'Semua sektor harus ada isinya sebelum menghitung.');
        }

        $sektors     = Commodity::whereNull('parent_id')->get();
        $resultsTemp = [];

        foreach ($sektors as $sektor) {

            $descendantIds = $this->getDescendantIds($sektor->id);
            $allIds        = array_merge([$sektor->id], $descendantIds);

            $dataHP = CommodityPriceProduction::where('tahun', $tahun)
                ->where('triwulan_id', $triwulan_id)
                ->whereIn('commodity_id', $allIds)
                ->get();

            $dataRasio = CommodityRasio::where('tahun', $tahun)
                ->where('triwulan_id', $triwulan_id)
                ->whereIn('commodity_id', $allIds)
                ->get();

            $dataIhp = CommodityIhp::where('tahun', $tahun)
                ->where('triwulan_id', $triwulan_id)
                ->whereIn('commodity_id', $allIds)
                ->get();

            if ($dataHP->isEmpty() || $dataRasio->isEmpty()) continue;

            $outputRupiah = $dataHP->sum(fn($item) => ($item->harga ?? 0) * ($item->produksi ?? 0));

            $avgRasio = (float) $dataRasio->avg('rasio_biaya_antara');
            if ($avgRasio > 1) $avgRasio = $avgRasio / 100;
            if ($avgRasio <= 0 || $avgRasio >= 1) continue;

            $ntbRupiah  = $outputRupiah * (1 - $avgRasio);
            $adhbRupiah = $ntbRupiah;

            $adhkRupiah = $adhbRupiah;
            if ($dataIhp->isNotEmpty()) {
                $ihpBerjalan = (float) $dataIhp->avg('ihp');
                if ($ihpBerjalan > 0) {
                    $adhkRupiah = $adhbRupiah * (100 / $ihpBerjalan);
                }
            }

            $resultsTemp[] = [
                'sektor_id' => $sektor->id,
                'output'    => $outputRupiah / self::KONVERSI_KE_MILIAR,
                'ntb'       => $ntbRupiah    / self::KONVERSI_KE_MILIAR,
                'adhb'      => $adhbRupiah   / self::KONVERSI_KE_MILIAR,
                'adhk'      => $adhkRupiah   / self::KONVERSI_KE_MILIAR,
            ];
        }

        // Total ADHB dalam Miliar Rp (untuk hitung kontribusi %)
        $totalAdhb = collect($resultsTemp)->sum('adhb');

        $lastYearData = PdrbResult::where('tahun', $tahun - 1)
            ->where('triwulan_id', $triwulan_id)
            ->get()
            ->keyBy('sektor_id');

        foreach ($resultsTemp as $row) {

            $kontribusi = ($totalAdhb > 0)
                ? ($row['adhb'] / $totalAdhb) * 100
                : 0;

            $adhkLalu    = $lastYearData->get($row['sektor_id'])?->adhk ?? null;
            $pertumbuhan = ($adhkLalu && $adhkLalu > 0)
                ? (($row['adhk'] - $adhkLalu) / $adhkLalu) * 100
                : null;

            PdrbResult::updateOrCreate(
                [
                    'sektor_id'   => $row['sektor_id'],
                    'tahun'       => $tahun,
                    'triwulan_id' => $triwulan_id,
                ],
                [
                    'output'      => $row['output'],   // Miliar Rp
                    'ntb'         => $row['ntb'],       // Miliar Rp
                    'adhb'        => $row['adhb'],      // Miliar Rp
                    'adhk'        => $row['adhk'],      // Miliar Rp
                    'kontribusi'  => $kontribusi,       // %
                    'pertumbuhan' => $pertumbuhan,      // %
                ]
            );
        }

        return back()->with('success', 'PDRB berhasil dihitung. Semua nilai tersimpan dalam Miliar Rp.');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'tahun'       => 'required|integer',
            'triwulan_id' => 'required|integer',
        ]);

        $deleted = PdrbResult::where('tahun', (int) $request->tahun)
            ->where('triwulan_id', (int) $request->triwulan_id)
            ->delete();

        if ($deleted === 0) {
            return back()->with('error', 'Tidak ada data yang dihapus.');
        }

        return redirect()
            ->route('pdrb.index', [
                'tahun'       => $request->tahun,
                'triwulan_id' => $request->triwulan_id,
            ])
            ->with('success', 'Data PDRB berhasil direset.');
    }
}