<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Form;
use Carbon\Carbon;

class DashboardKetuaController extends Controller
{
    public function index(Request $request)
    {
        $user   = auth()->user();
        $timId  = $user->tim_id;
        $selectedYear = $request->get('year', Carbon::now()->year);

        $timName = $user->tim ? $user->tim->nama_tim : '-';

        // --- daftar tahun untuk dropdown (hanya tahun yg ada di tim ini) ---
        $availableYears = Form::where('tim_id', $timId)
            ->selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // --- KPI CARDS ---
        $totalDinasTahun = Form::where('tim_id', $timId)
            ->whereYear('tanggal', $selectedYear)
            ->count();

        $totalDinasBulan = Form::where('tim_id', $timId)
            ->whereYear('tanggal', $selectedYear)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->count();

        $rataRataBulan = $totalDinasTahun > 0 ? round($totalDinasTahun / 12, 1) : 0;

        // --- PIE DATA (jenis kegiatan untuk tim ini) ---
        $pie = Form::where('tim_id', $timId)
            ->whereYear('tanggal', $selectedYear)
            ->select('kegiatan_id', DB::raw('COUNT(*) as total'))
            ->groupBy('kegiatan_id')
            ->with('manageKegiatan')
            ->get();

        $pieLabels = $pie->pluck('manageKegiatan.nama_kegiatan');
        $pieData   = $pie->pluck('total');

        // --- BAR DATA (jumlah kegiatan per jenis) ---
        $barLabels = $pieLabels;
        $barData   = $pieData;

        // --- LINE DATA (tren bulanan per orang di tim) ---
        $lineLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $lineRaw = Form::where('forms.tim_id', $timId)
            ->whereYear('forms.tanggal', $selectedYear)
            ->join('users', 'forms.user_id', '=', 'users.id')
            ->selectRaw('users.id as user_id, users.username, MONTH(forms.tanggal) as bulan, COUNT(*) as total')
            ->groupBy('users.id', 'users.username', 'bulan')
            ->get();

        $users = $lineRaw->pluck('username', 'user_id')->unique();

        $lineDatasets = [];
        $colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#20c997', '#6f42c1'];

        foreach ($users as $userId => $username) {
            $data = [];
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $count = $lineRaw
                    ->where('user_id', $userId)
                    ->where('bulan', $bulan)
                    ->pluck('total')
                    ->first() ?? 0;
                $data[] = $count;
            }
            $lineDatasets[] = [
                'label' => $username,
                'data' => $data,
                'borderWidth' => 2,
                'fill' => false,
                'borderColor' => $colors[count($lineDatasets) % count($colors)]
            ];
        }

        // --- STACKED BAR DATA ---
        $jenisKegiatanData = Form::select(
            'users.username as nama_user',
            'kegiatan.nama_kegiatan',
            DB::raw('COUNT(*) as total')
        )
            ->join('users', 'forms.user_id', '=', 'users.id')
            ->join('kegiatan', 'forms.kegiatan_id', '=', 'kegiatan.id')
            ->whereYear('forms.tanggal', $selectedYear)
            ->where('forms.tim_id', $timId) // 👈 filter hanya tim yang sedang login
            ->groupBy('users.username', 'kegiatan.nama_kegiatan')
            ->get();

        $userLabels   = $jenisKegiatanData->pluck('nama_user')->unique()->values();
        $jenisLabels  = $jenisKegiatanData->pluck('nama_kegiatan')->unique()->values();

        $stackedDatasets = [];
        $colorsStacked = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'];

        foreach ($jenisLabels as $i => $jenis) {
            $data = [];
            foreach ($userLabels as $user) {
                $row = $jenisKegiatanData
                    ->where('nama_user', $user)
                    ->where('nama_kegiatan', $jenis)
                    ->first();
                $data[] = $row ? $row->total : 0;
            }
            $stackedDatasets[] = [
                'label' => $jenis,
                'data' => $data,
                'backgroundColor' => $colorsStacked[$i % count($colorsStacked)]
            ];
        }

        return view('dashboardKetua', compact(
            'selectedYear',
            'availableYears',
            'timName',
            'totalDinasTahun',
            'totalDinasBulan',
            'rataRataBulan',
            'pieLabels',
            'pieData',
            'barLabels',
            'barData',
            'lineLabels',
            'lineDatasets',
            'userLabels',
            'stackedDatasets'
        ));
    }
}
