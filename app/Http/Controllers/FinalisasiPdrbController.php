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

            // --- Data triwulan sebelumnya (untuk QoQ) ---
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

            // --- Data tahun lalu, triwulan sama (untuk YoY) ---
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

        // --- Riwayat finalisasi: semua versi yang pernah dikunci ---
        // Ambil satu baris per (tahun, triwulan_id, versi) dengan agregate PDRB-nya
        $riwayat = PdrbFinal::with(['triwulan', 'finalizer'])
            ->select(
                'tahun',
                'triwulan_id',
                'versi',
                'status',
                'finalized_by',
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
        ]);

        $tahun       = $request->tahun;
        $triwulan_id = $request->triwulan_id;

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

    /**
     * Tampilkan detail snapshot sektor untuk satu versi finalisasi tertentu.
     * Dipanggil via AJAX dari modal riwayat.
     */
    public function detailVersi(Request $request)
    {
        $request->validate([
            'tahun'       => 'required|integer',
            'triwulan_id' => 'required|integer',
            'versi'       => 'required|integer',
        ]);

        $rows = PdrbFinal::with('sektor')
            ->where('tahun', $request->tahun)
            ->where('triwulan_id', $request->triwulan_id)
            ->where('versi', $request->versi)
            ->get()
            ->map(fn ($r) => [
                'sektor'      => $r->sektor->nama ?? '-',
                'adhb'        => $r->adhb,
                'adhk'        => $r->adhk,
                'kontribusi'  => $r->kontribusi,
                'pertumbuhan' => $r->pertumbuhan,
            ]);

        return response()->json($rows);
    }
}