<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PdrbResult;
use App\Models\PdrbFinal;
use App\Models\CommodityPriceProduction;
use App\Models\Triwulan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FinalisasiPdrbController extends Controller
{
    public function index(Request $request)
    {
        $tahun       = $request->tahun;
        $triwulan_id = $request->triwulan_id;

        $data          = collect();
        $total_adhb    = 0;
        $total_adhk    = 0;
        $isFinal       = false;
        $versiTerakhir = null;
        $totalQoq      = null;
        $totalYoy      = null;

        if ($tahun && $triwulan_id) {

            $data = PdrbResult::with('sektor')
                ->where('tahun', $tahun)
                ->where('triwulan_id', $triwulan_id)
                ->get();

            $prevTriwulan = $triwulan_id - 1;
            $prevTahun    = $tahun;
            if ($triwulan_id == 1) {
                $prevTriwulan = 4;
                $prevTahun    = $tahun - 1;
            }

            $prevData = PdrbResult::where('tahun', $prevTahun)
                ->where('triwulan_id', $prevTriwulan)
                ->get()
                ->keyBy('sektor_id');

            $yoyData = PdrbResult::where('tahun', $tahun - 1)
                ->where('triwulan_id', $triwulan_id)
                ->get()
                ->keyBy('sektor_id');

            foreach ($data as $row) {
                $adhkLalu = $prevData->get($row->sektor_id)?->adhk;
                $row->qoq = ($adhkLalu && $adhkLalu > 0)
                    ? (($row->adhk - $adhkLalu) / $adhkLalu) * 100
                    : null;

                $adhkYoy = $yoyData->get($row->sektor_id)?->adhk;
                $row->pertumbuhan = ($adhkYoy && $adhkYoy > 0)
                    ? (($row->adhk - $adhkYoy) / $adhkYoy) * 100
                    : null;
            }

            $total_adhb = $data->sum('adhb');
            $total_adhk = $data->sum('adhk');

            $prevTotalAdhk = $prevData->sum('adhk');
            $totalQoq = ($prevTotalAdhk > 0)
                ? (($total_adhk - $prevTotalAdhk) / $prevTotalAdhk) * 100
                : null;

            $yoyTotalAdhk = $yoyData->sum('adhk');
            $totalYoy = ($yoyTotalAdhk > 0)
                ? (($total_adhk - $yoyTotalAdhk) / $yoyTotalAdhk) * 100
                : null;

            $versiTerakhir = PdrbFinal::where('tahun', $tahun)
                ->where('triwulan_id', $triwulan_id)
                ->max('versi');

            $isFinal = $versiTerakhir !== null;
        }

        $riwayat = PdrbFinal::with(['triwulan', 'finalizer'])
            ->select(
                'tahun',
                'triwulan_id',
                'versi',
                'status',
                'finalized_by',
                DB::raw('MAX(komentar) as komentar'),
                DB::raw('MIN(created_at) as created_at'),
                DB::raw('SUM(adhb) as total_adhb'),
                DB::raw('SUM(adhk) as total_adhk'),
                DB::raw('COUNT(DISTINCT sektor_id) as jumlah_sektor')
            )
            ->groupBy('tahun', 'triwulan_id', 'versi', 'status', 'finalized_by')
            ->orderByDesc('created_at')
            ->get();

        $tahunList = CommodityPriceProduction::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $triwulans = Triwulan::all();

        return view('finalisasi.index', compact(
            'data',
            'tahun',
            'triwulan_id',
            'total_adhb',
            'total_adhk',
            'isFinal',
            'versiTerakhir',
            'tahunList',
            'triwulans',
            'totalQoq',
            'totalYoy',
            'riwayat'
        ));
    }

    public function finalisasi(Request $request)
    {
        $request->validate([
            'tahun'       => 'required|integer',
            'triwulan_id' => 'required|exists:triwulanan,id',
            'komentar'    => 'required|string|min:5|max:1000',
        ], [
            'komentar.required' => 'Komentar finalisasi wajib diisi.',
            'komentar.min'      => 'Komentar minimal 5 karakter.',
            'komentar.max'      => 'Komentar maksimal 1000 karakter.',
        ]);

        $tahun       = $request->tahun;
        $triwulan_id = $request->triwulan_id;
        $komentar    = trim($request->komentar);

        DB::beginTransaction();

        try {
            $lastVersion = PdrbFinal::where('tahun', $tahun)
                ->where('triwulan_id', $triwulan_id)
                ->max('versi');

            $newVersion = $lastVersion ? $lastVersion + 1 : 1;

            $data = PdrbResult::where('tahun', $tahun)
                ->where('triwulan_id', $triwulan_id)
                ->get();

            if ($data->isEmpty()) {
                DB::rollback();
                return back()->with('error', 'Data PDRB belum tersedia!');
            }

            foreach ($data as $item) {
                PdrbFinal::create([
                    'tahun'        => $item->tahun,
                    'triwulan_id'  => $item->triwulan_id,
                    'sektor_id'    => $item->sektor_id,
                    'output'       => $item->output,
                    'ntb'          => $item->ntb,
                    'adhb'         => $item->adhb,
                    'adhk'         => $item->adhk,
                    'kontribusi'   => $item->kontribusi ?? null,
                    'pertumbuhan'  => $item->pertumbuhan ?? null,
                    'versi'        => $newVersion,
                    'status'       => 'final',
                    'komentar'     => $komentar,
                    'finalized_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return back()->with('success', "Finalisasi berhasil (versi $newVersion)");

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Gagal finalisasi: ' . $e->getMessage());
        }
    }

    public function detailVersi(Request $request)
    {
        $request->validate([
            'tahun'       => 'required|integer',
            'triwulan_id' => 'required|integer',
            'versi'       => 'required|integer',
        ]);

        $tahun = (int) $request->tahun;
        $tw    = (int) $request->triwulan_id;

        $snapshot = PdrbFinal::with('sektor')
            ->where('tahun', $tahun)
            ->where('triwulan_id', $tw)
            ->where('versi', $request->versi)
            ->orderBy('sektor_id')
            ->get();

        // Periode pembanding
        $prevTw    = $tw == 1 ? 4 : $tw - 1;
        $prevTahun = $tw == 1 ? $tahun - 1 : $tahun;

        $prev = $this->adhkPembanding($prevTahun, $prevTw);   // QoQ
        $yoy  = $this->adhkPembanding($tahun - 1, $tw);       // YoY

        $growth = fn ($now, $base) => ($base && $base > 0)
            ? (($now - $base) / $base) * 100
            : null;

        $rows = $snapshot->map(function ($r) use ($prev, $yoy, $growth) {
            $adhk = (float) $r->adhk;

            return [
                'sektor'     => $r->sektor->nama ?? '-',
                'adhb'       => (float) $r->adhb,
                'adhk'       => $adhk,
                'kontribusi' => $r->kontribusi !== null ? (float) $r->kontribusi : null,
                'qoq'        => $growth($adhk, $prev->get($r->sektor_id)),
                'yoy'        => $growth($adhk, $yoy->get($r->sektor_id)),
            ];
        })->values();

        $totalAdhb = $rows->sum('adhb');
        $totalAdhk = $rows->sum('adhk');

        return response()->json([
            'rows'  => $rows,
            'total' => [
                'adhb' => $totalAdhb,
                'adhk' => $totalAdhk,
                'qoq'  => $growth($totalAdhk, $prev->sum()),
                'yoy'  => $growth($totalAdhk, $yoy->sum()),
            ],
        ]);
    }

    private function adhkPembanding(int $tahun, int $triwulanId)
    {
        $versi = PdrbFinal::where('tahun', $tahun)
            ->where('triwulan_id', $triwulanId)
            ->max('versi');

        if ($versi !== null) {
            return PdrbFinal::where('tahun', $tahun)
                ->where('triwulan_id', $triwulanId)
                ->where('versi', $versi)
                ->get()
                ->keyBy('sektor_id')
                ->map(fn ($r) => (float) $r->adhk);
        }

        return PdrbResult::where('tahun', $tahun)
            ->where('triwulan_id', $triwulanId)
            ->get()
            ->keyBy('sektor_id')
            ->map(fn ($r) => (float) $r->adhk);
    }
}