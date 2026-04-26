<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PdrbFinal;
use App\Models\Triwulan;
use App\Models\EconomicGroup;
use Illuminate\Support\Facades\DB;

class DashboardExportController extends Controller
{
    private function latestIdsQuery(int $tahun, int $triwulanId): \Illuminate\Database\Query\Builder
    {
        return DB::table('pdrb_final')
            ->select(DB::raw('MAX(id) as id'))
            ->where('tahun', $tahun)
            ->where('triwulan_id', $triwulanId)
            ->groupBy('sektor_id');
    }

    public function exportPdf(Request $request)
    {
        $tahun    = $request->tahun    ? (int) $request->tahun    : null;
        $triwulan = $request->triwulan ? (int) $request->triwulan : null;

        if (! $tahun || ! $triwulan) {
            return redirect()->route('dashboard.index')
                ->with('error', 'Parameter tahun dan triwulan diperlukan untuk export.');
        }

        // Label triwulan
        $triwulanObj   = Triwulan::find($triwulan);
        $triwulan_label = $triwulanObj ? $triwulanObj->triwulan : 'Triwulan ' . $triwulan;

        $economicGroups = EconomicGroup::orderBy('urutan')->get();

        $idToKelompok = [];
        $kelompokIds  = [];
        foreach ($economicGroups as $grp) {
            $commodityIds = DB::table('commodities')
                ->join('economic_group_commodities as egc', 'commodities.kode', '=', 'egc.commodity_kode')
                ->where('egc.economic_group_id', $grp->id)
                ->pluck('commodities.id')
                ->toArray();

            $kelompokIds[$grp->key] = $commodityIds;

            foreach ($commodityIds as $cid) {
                $idToKelompok[$cid] = $grp->key;
            }
        }

        // Data periode terpilih: hanya versi terbaru per sektor
        $latestIds = $this->latestIdsQuery($tahun, $triwulan);

        $data = PdrbFinal::with('sektor')
            ->whereIn('id', $latestIds)
            ->orderBy('sektor_id')
            ->get();

        if ($data->isEmpty()) {
            return redirect()->route('dashboard.index')
                ->with('error', 'Data tidak ditemukan untuk periode ' . $tahun . ' ' . $triwulan_label . '.');
        }

        $total_adhb = $data->sum('adhb');
        $total_adhk = $data->sum('adhk');

        // Data previous (QoQ & YoY): hanya versi terbaru per sektor
        $prev_tw_id     = $triwulan == 1 ? 4          : $triwulan - 1;
        $prev_tahun_qoq = $triwulan == 1 ? $tahun - 1 : $tahun;
        $prev_tahun_yoy = $tahun - 1;

        $latestIdsQoq = $this->latestIdsQuery($prev_tahun_qoq, $prev_tw_id);
        $latestIdsYoy = $this->latestIdsQuery($prev_tahun_yoy, $triwulan);

        $prev_qoq = PdrbFinal::whereIn('id', $latestIdsQoq)->get()->keyBy('sektor_id');
        $prev_yoy = PdrbFinal::whereIn('id', $latestIdsYoy)->get()->keyBy('sektor_id');

        foreach ($data as $item) {
            $sid = $item->sektor_id;

            $item->kontribusi = $total_adhb > 0
                ? round(($item->adhb / $total_adhb) * 100, 2)
                : 0;

            $adhk_prev_qoq    = $prev_qoq[$sid]->adhk ?? 0;
            $item->growth_qoq = $adhk_prev_qoq > 0
                ? round((($item->adhk - $adhk_prev_qoq) / $adhk_prev_qoq) * 100, 2)
                : 0;

            $adhk_prev_yoy    = $prev_yoy[$sid]->adhk ?? 0;
            $item->growth_yoy = $adhk_prev_yoy > 0
                ? round((($item->adhk - $adhk_prev_yoy) / $adhk_prev_yoy) * 100, 2)
                : 0;

            $item->kelompok = $idToKelompok[$sid] ?? 'tersier';
        }

        // Total growth
        $prev_total_qoq = $prev_qoq->sum('adhk');
        $prev_total_yoy = $prev_yoy->sum('adhk');

        $growth_qoq_total = $prev_total_qoq > 0
            ? round((($total_adhk - $prev_total_qoq) / $prev_total_qoq) * 100, 2)
            : 0;
        $growth_yoy_total = $prev_total_yoy > 0
            ? round((($total_adhk - $prev_total_yoy) / $prev_total_yoy) * 100, 2)
            : 0;

        // Top5 / Bottom5
        $top5    = $data->sortByDesc('growth_yoy')->take(5)->values();
        $bottom5 = $data->sortBy('growth_yoy')->take(5)->values();

        // Chart: Trend ADHK & ADHB
        $trend_labels = [];
        $trend_adhk   = [];
        $trend_adhb   = [];

        $trend_raw = DB::table('pdrb_final as p')
            ->select('p.tahun', 'p.triwulan_id',
                DB::raw('SUM(p.adhk) as total_adhk'),
                DB::raw('SUM(p.adhb) as total_adhb'))
            ->joinSub(
                DB::table('pdrb_final')
                    ->select('tahun', 'triwulan_id', 'sektor_id', DB::raw('MAX(id) as max_id'))
                    ->groupBy('tahun', 'triwulan_id', 'sektor_id'),
                'latest',
                function ($join) {
                    $join->on('p.tahun',       '=', 'latest.tahun')
                         ->on('p.triwulan_id', '=', 'latest.triwulan_id')
                         ->on('p.sektor_id',   '=', 'latest.sektor_id')
                         ->on('p.id',          '=', 'latest.max_id');
                }
            )
            ->groupBy('p.tahun', 'p.triwulan_id')
            ->orderBy('p.tahun')
            ->orderBy('p.triwulan_id')
            ->get();

        foreach ($trend_raw as $row) {
            $trend_labels[] = $row->tahun . ' TW' . $row->triwulan_id;
            $trend_adhk[]   = round($row->total_adhk, 2);
            $trend_adhb[]   = round($row->total_adhb, 2);
        }

        // Growth per sektor
        $growth_labels = [];
        $growth_data   = [];

        foreach ($data as $item) {
            $nama            = $item->sektor->nama ?? '-';
            $growth_labels[] = mb_strlen($nama) > 25 ? mb_substr($nama, 0, 23) . '…' : $nama;
            $growth_data[]   = $item->growth_yoy;
        }

        // Struktur ekonomi
        $struktur_data = [];

        foreach ($economicGroups as $grp) {
            $sektorIds = $kelompokIds[$grp->key] ?? [];

            $subData     = $data->filter(fn($r) => in_array($r->sektor_id, $sektorIds));
            $sum_adhb    = $subData->sum('adhb');
            $sum_adhk    = $subData->sum('adhk');
            $pct_kontrib = $total_adhb > 0
                ? round(($sum_adhb / $total_adhb) * 100, 2)
                : 0;

            $adhk_prev  = $prev_yoy
                ->filter(fn($r) => in_array($r->sektor_id, $sektorIds))
                ->sum('adhk');
            $growth_grp = $adhk_prev > 0
                ? round((($sum_adhk - $adhk_prev) / $adhk_prev) * 100, 2)
                : 0;

            $sub_sektors = $subData->sortByDesc('kontribusi')->take(5)->map(fn($r) => [
                'nama'       => $r->sektor->nama ?? '-',
                'kontribusi' => $r->kontribusi,
                'growth_yoy' => $r->growth_yoy,
            ])->values()->toArray();

            $struktur_data[] = [
                'key'         => $grp->key,
                'label'       => $grp->label,
                'warna'       => $grp->warna,
                'pct'         => $pct_kontrib,
                'adhb'        => round($sum_adhb, 2),
                'adhk'        => round($sum_adhk, 2),
                'growth_yoy'  => $growth_grp,
                'sub_sektors' => $sub_sektors,
                'jml_sektor'  => $subData->count(),
            ];
        }

        return view('dashboard.export-pdf', compact(
            'data',
            'tahun',
            'triwulan',
            'triwulan_label',
            'total_adhb',
            'total_adhk',
            'growth_qoq_total',
            'growth_yoy_total',
            'top5',
            'bottom5',
            'trend_labels',
            'trend_adhk',
            'trend_adhb',
            'growth_labels',
            'growth_data',
            'struktur_data'
        ));
    }
}