<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Form;
use App\Models\Tim;
use Carbon\Carbon;

class DashboardAnggotaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $selectedYear = $request->get('year', Carbon::now()->year);

        // --- daftar tahun untuk dropdown
        $availableYears = Form::selectRaw('YEAR(tanggal) as year')
            ->where('user_id', $user->id)
            ->distinct()
            ->pluck('year');

        // --- total keluar dinas tahun ini (user login)
        $totalDinasTahun = Form::where('user_id', $user->id)
            ->whereYear('tanggal', $selectedYear)
            ->count();

        // --- total keluar dinas bulan ini (user login)
        $totalDinasBulan = Form::where('user_id', $user->id)
            ->whereYear('tanggal', $selectedYear)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->count();

        // --- rata-rata keluar dinas / bulan
        $rataRataBulan = Form::where('user_id', $user->id)
            ->whereYear('tanggal', $selectedYear)
            ->selectRaw('COUNT(*) / COUNT(DISTINCT MONTH(tanggal)) as avg')
            ->value('avg') ?? 0;

        // --- Pie Chart: distribusi jenis kegiatan user
        $pieDataRaw = Form::select(
            'kegiatan.nama_kegiatan',
            DB::raw('COUNT(*) as total')
        )
            ->join('kegiatan', 'forms.kegiatan_id', '=', 'kegiatan.id')
            ->where('forms.user_id', $user->id)
            ->whereYear('forms.tanggal', $selectedYear)
            ->groupBy('kegiatan.nama_kegiatan')
            ->get();

        $pieLabels = $pieDataRaw->pluck('nama_kegiatan');
        $pieData   = $pieDataRaw->pluck('total');

        // --- Bar Chart: jumlah kegiatan user login per jenis kegiatan
        $barLabels = $pieLabels; // sama dengan pie
        $barData   = $pieData;

        // --- Line Chart: tren bulanan user login
        $lineRaw = Form::select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('COUNT(*) as total')
        )
            ->where('user_id', $user->id)
            ->whereYear('tanggal', $selectedYear)
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->pluck('total', 'bulan');

        $lineLabels = [];
        $lineValues = [];
        for ($m = 1; $m <= 12; $m++) {
            $lineLabels[] = Carbon::create()->month($m)->format('F');
            $lineValues[] = $lineRaw[$m] ?? 0;
        }

        $lineDatasets = [[
            'label' => $user->username,
            'data' => $lineValues,
            'borderColor' => '#4e73df',
            'backgroundColor' => 'rgba(78, 115, 223, 0.1)',
            'fill' => true,
            'tension' => 0.3
        ]];

        return view('dashboardAnggota', compact(
            'availableYears',
            'selectedYear',
            'totalDinasTahun',
            'totalDinasBulan',
            'rataRataBulan',
            'pieLabels',
            'pieData',
            'barLabels',
            'barData',
            'lineLabels',
            'lineDatasets'
        ));
    }
}
