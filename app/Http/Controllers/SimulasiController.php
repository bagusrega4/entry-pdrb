<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SimulasiController extends Controller
{
    public function index()
    {
        $datasets = DB::table('io_datasets')->orderByDesc('tahun')->get();

        $sektors = DB::table('commodities')
            ->whereNull('parent_id')->orderBy('id')->get();

        $riwayat_singkat = DB::table('simulasi_riwayat')
            ->where('created_by', auth()->id())
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        [$pdrbAdhb, $pdrbAdhk, $pdrbPeriode] = $this->getPdrbReferensi();
        $pdrbInfo = $pdrbPeriode ? [
            'tahun'      => $pdrbPeriode->tahun,
            'triwulan'   => $pdrbPeriode->triwulan_label,
            'total_adhb' => $pdrbAdhb,
            'total_adhk' => $pdrbAdhk,
        ] : null;

        return view('simulasi.index', compact(
            'datasets', 'sektors', 'riwayat_singkat', 'pdrbInfo'
        ));
    }

    // PROSES SIMULASI
    public function proses(Request $request)
    {
        $request->validate([
            'dataset_id' => 'required',
            'sektor'     => 'required|array',
            'nilai'      => 'required|array',
        ]);

        $datasetId = $request->dataset_id;
        $dataset   = DB::table('io_datasets')->where('id', $datasetId)->first();
        if (!$dataset) return back()->with('error', 'Dataset IO tidak ditemukan.');

        $n = $dataset->jumlah_sektor;

        [$z, $x] = $this->loadMatrixData($datasetId, $n);
        if (is_string($z)) return back()->with('error', $z);

        $L = $this->buildLeontief($z, $x, $n);
        if (!$L) return back()->with('error', 'Matrix (I-A) singular, tidak dapat diinvers.');

        [$backward, $forward, $normBackward, $normForward, $klasifikasi] =
            $this->hitungLinkage($L, $n);

        // Ambil rasio biaya antara per sektor
        $rasioPerSektor = $this->getRasioPerSektor($n, $dataset->tahun ?? null);

        $Y = array_fill(1, $n, 0.0);
        foreach ($request->sektor as $k => $s) {
            $s = (int) $s;
            if ($s >= 1 && $s <= $n) $Y[$s] += (float) $request->nilai[$k];
        }

        $namaSektor         = $this->getNamaSektor();
        $hasil              = [];
        $totalTambahanOutput = 0;
        $totalTambahanNtb   = 0;

        for ($i = 1; $i <= $n; $i++) {
            $tambahan = 0;
            for ($j = 1; $j <= $n; $j++) $tambahan += $L[$i][$j] * $Y[$j];

            $rasio      = $rasioPerSektor[$i] ?? 0.5;
            $tambahanNtb = $tambahan * (1 - $rasio);

            $hasil[] = [
                'sektor'              => $i,
                'nama'                => $namaSektor[$i - 1] ?? 'Sektor ' . $i,
                'output_awal'         => $x[$i],
                'tambahan_output'     => $tambahan,
                'tambahan_ntb'        => $tambahanNtb,
                'rasio_biaya_antara'  => $rasio,
                'output_baru'         => $x[$i] + $tambahan,
                'persen_dampak'       => $x[$i] > 0 ? ($tambahan / $x[$i]) * 100 : 0,
                'backward'            => round($backward[$i], 4),
                'forward'             => round($forward[$i], 4),
                'norm_backward'       => round($normBackward[$i], 4),
                'norm_forward'        => round($normForward[$i], 4),
                'klasifikasi'         => $klasifikasi[$i],
            ];

            $totalTambahanOutput += $tambahan;
            $totalTambahanNtb    += $tambahanNtb;
        }

        [$pdrbAdhb, $pdrbAdhk, $pdrbPeriode] = $this->getPdrbReferensi();
        $totalStimulus = array_sum(array_map('floatval', $request->nilai));

        // Linkage data
        $linkage_data = [];
        for ($i = 1; $i <= $n; $i++) {
            $linkage_data[] = [
                'nama'        => $namaSektor[$i - 1] ?? 'Sektor ' . $i,
                'backward'    => round($normBackward[$i], 4),
                'forward'     => round($normForward[$i], 4),
                'klasifikasi' => $klasifikasi[$i],
            ];
        }

        $sektorInjeksi = [];
        foreach ($request->sektor as $k => $s) {
            $s = (int) $s;
            if ($s >= 1 && $s <= $n) {
                $sektorInjeksi[] = [
                    'sektor' => $s,
                    'nama'   => $namaSektor[$s - 1] ?? 'Sektor ' . $s,
                    'nilai'  => (float) $request->nilai[$k],
                ];
            }
        }

        session([
            'simulasi_input' => [
                'dataset_id' => $datasetId,
                'sektor'     => $request->sektor,
                'nilai'      => $request->nilai,
            ],
            'hasil_simulasi'   => $hasil,
            'linkage_data'     => $linkage_data,
            'summary_simulasi' => [
                'dataset'              => $dataset->nama_dataset,
                'tahun'                => $dataset->tahun,
                'dataset_id'           => $datasetId,
                'sektor_injeksi'       => $sektorInjeksi,

                // ADHB
                'pdrb_adhb_awal'       => $pdrbAdhb,
                'pdrb_adhb_baru'       => $pdrbAdhb + $totalTambahanNtb,
                'persen_naik_adhb'     => $pdrbAdhb > 0
                    ? ($totalTambahanNtb / $pdrbAdhb) * 100 : 0,

                // ADHK
                'pdrb_adhk_awal'       => $pdrbAdhk,
                'pdrb_adhk_baru'       => $pdrbAdhk > 0 && $pdrbAdhb > 0
                    ? $pdrbAdhk + $totalTambahanNtb * ($pdrbAdhk / $pdrbAdhb)
                    : $pdrbAdhk + $totalTambahanNtb,
                'persen_naik_adhk'     => $pdrbAdhk > 0
                    ? ($totalTambahanNtb * ($pdrbAdhb > 0 ? $pdrbAdhk / $pdrbAdhb : 1) / $pdrbAdhk) * 100
                    : 0,

                // Referensi Periode 
                'pdrb_tahun'           => $pdrbPeriode->tahun ?? null,
                'pdrb_triwulan'        => $pdrbPeriode->triwulan_label ?? null,

                // Agregat Simulasi 
                'tambahan_output'      => $totalTambahanOutput,
                'tambahan_ntb'         => $totalTambahanNtb,
                'total_stimulus'       => $totalStimulus,
                'multiplier_output'    => $totalStimulus > 0
                    ? $totalTambahanOutput / $totalStimulus : 0,
                'multiplier_ntb'       => $totalStimulus > 0 
                    ? $totalTambahanNtb / $totalStimulus : 0,

                // Backward compatibility
                'pdrb_awal'            => $pdrbAdhb,
                'pdrb_baru'            => $pdrbAdhb + $totalTambahanNtb,
                'persen_naik'          => $pdrbAdhb > 0
                    ? ($totalTambahanNtb / $pdrbAdhb) * 100 : 0,
                'multiplier'           => $totalStimulus > 0
                    ? $totalTambahanOutput / $totalStimulus : 0,
            ],
        ]);

        return redirect()->route('simulasi.index')->with('success', 'Simulasi berhasil dijalankan.');
    }

    // SKENARIO KOMPARASI
    public function skenario()
    {
        return view('simulasi.skenario', [
            'datasets'  => DB::table('io_datasets')->orderByDesc('tahun')->get(),
            'sektors'   => DB::table('commodities')->whereNull('parent_id')->orderBy('id')->get(),
            'hasil_a'   => session('skenario_a'),
            'hasil_b'   => session('skenario_b'),
            'summary_a' => session('skenario_a_summary'),
            'summary_b' => session('skenario_b_summary'),
        ]);
    }

    public function prosesSkenario(Request $request)
    {
        $request->validate([
            'dataset_id_a' => 'required', 'sektor_a' => 'required|array', 'nilai_a' => 'required|array',
            'dataset_id_b' => 'required', 'sektor_b' => 'required|array', 'nilai_b' => 'required|array',
        ]);

        $hasilA = $this->hitungSkenario($request->dataset_id_a, $request->sektor_a, $request->nilai_a, $request->label_a ?? 'Skenario A');
        $hasilB = $this->hitungSkenario($request->dataset_id_b, $request->sektor_b, $request->nilai_b, $request->label_b ?? 'Skenario B');

        if (isset($hasilA['error'])) return back()->with('error', 'Skenario A: ' . $hasilA['error']);
        if (isset($hasilB['error'])) return back()->with('error', 'Skenario B: ' . $hasilB['error']);

        session([
            'skenario_a'         => $hasilA['hasil'],
            'skenario_b'         => $hasilB['hasil'],
            'skenario_a_summary' => $hasilA['summary'],
            'skenario_b_summary' => $hasilB['summary'],
        ]);

        return redirect()->route('simulasi.skenario')->with('success', 'Komparasi berhasil dihitung.');
    }

    public function resetSkenario()
    {
        session()->forget(['skenario_a', 'skenario_b', 'skenario_a_summary', 'skenario_b_summary']);
        return redirect()->route('simulasi.skenario')->with('success', 'Komparasi berhasil direset.');
    }

    // RIWAYAT
    public function riwayat()
    {
        $riwayat = DB::table('simulasi_riwayat')
            ->where('created_by', auth()->id())  // tambah ini
            ->orderByDesc('created_at')->get()
            ->map(function ($r) {
                $r->summary = json_decode($r->summary, true);
                $r->hasil   = json_decode($r->hasil,   true);
                return $r;
            });

        return view('simulasi.riwayat', compact('riwayat'));
    }

    public function simpan(Request $request)
    {
        $summary = session('summary_simulasi');
        $hasil   = session('hasil_simulasi');

        if (!$summary || !$hasil) {
            return back()->with('error', 'Tidak ada hasil simulasi yang bisa disimpan.');
        }

        DB::table('simulasi_riwayat')->insert([
            'nama_simulasi' => $request->nama_simulasi ?? ('Simulasi ' . now()->format('d/m/Y H:i')),
            'dataset_id'    => $summary['dataset_id'],
            'summary'       => json_encode($summary),
            'hasil'         => json_encode($hasil),
            'created_by'    => auth()->id(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return back()->with('success', 'Simulasi berhasil disimpan ke riwayat.');
    }

    public function hapus($id)
    {
        $row = DB::table('simulasi_riwayat')
            ->where('id', $id)
            ->where('created_by', auth()->id())
            ->first();

        if (!$row) return back()->with('error', 'Riwayat tidak ditemukan atau Anda tidak memiliki akses.');

        DB::table('simulasi_riwayat')->where('id', $id)->delete();
        return back()->with('success', 'Riwayat berhasil dihapus.');
    }

    public function lihatRiwayat($id)
    {
        $row = DB::table('simulasi_riwayat')
            ->where('id', $id)
            ->where('created_by', auth()->id())
            ->first();

        if (!$row) return redirect()->route('simulasi.riwayat')
            ->with('error', 'Riwayat tidak ditemukan atau Anda tidak memiliki akses.');

        $summary     = json_decode($row->summary, true);
        $hasil       = json_decode($row->hasil, true);

        $linkageData = array_map(fn($r) => [
            'nama'        => $r['nama'],
            'backward'    => $r['norm_backward'] ?? 0,
            'forward'     => $r['norm_forward']  ?? 0,
            'klasifikasi' => $r['klasifikasi']   ?? 'Independen',
        ], $hasil);

        return view('simulasi.lihat-riwayat', compact('row', 'summary', 'hasil', 'linkageData'));
    }

    // EXPORT index
    public function exportExcel()
    {
        $hasil   = session('hasil_simulasi');
        $summary = session('summary_simulasi');

        if (!$hasil || !$summary) return back()->with('error', 'Tidak ada data untuk diekspor.');

        $lines = [
            'HASIL SIMULASI INPUT-OUTPUT',
            'Dataset:,' . $summary['dataset'],
            'Tahun IO:,' . $summary['tahun'],
            'PDRB Referensi:,' . ($summary['pdrb_tahun'] ?? '-') . ' ' . ($summary['pdrb_triwulan'] ?? ''),
            '',
            '── RINGKASAN EKONOMI',
            'Total Stimulus (Miliar Rp):,'         . number_format($summary['total_stimulus'],    3, '.', ''),
            'Tambahan Output ΔX (Miliar Rp):,'     . number_format($summary['tambahan_output'],   3, '.', ''),
            'Tambahan NTB ΔNTB (Miliar Rp):,'      . number_format($summary['tambahan_ntb'] ?? 0, 3, '.', ''),
            '',
            '── ANALISIS ADHB (Harga Berlaku)',
            'PDRB ADHB Awal (Miliar Rp):,'         . number_format($summary['pdrb_adhb_awal']   ?? $summary['pdrb_awal'], 3, '.', ''),
            'PDRB ADHB Baru Estimasi (Miliar Rp):,'. number_format($summary['pdrb_adhb_baru']   ?? $summary['pdrb_baru'], 3, '.', ''),
            'Dampak thd PDRB ADHB (%):,'           . number_format($summary['persen_naik_adhb'] ?? $summary['persen_naik'], 4, '.', ''),
            '',
            '── ANALISIS ADHK (Harga Konstan)',
            'PDRB ADHK Awal (Miliar Rp):,'         . number_format($summary['pdrb_adhk_awal']   ?? 0, 3, '.', ''),
            'PDRB ADHK Baru Estimasi (Miliar Rp):,'. number_format($summary['pdrb_adhk_baru']   ?? 0, 3, '.', ''),
            'Dampak thd PDRB ADHK (%):,'           . number_format($summary['persen_naik_adhk'] ?? 0, 4, '.', ''),
            '',
            '── MULTIPLIER',
            'Output Multiplier (ΔX/ΔY):,'          . number_format($summary['multiplier_output'] ?? $summary['multiplier'], 4, '.', ''),
            'NTB Multiplier (ΔNTB/ΔY):,'           . number_format($summary['multiplier_ntb']    ?? 0, 4, '.', ''),
            '',
            'No,Sektor,Output Awal (M Rp),Tambahan ΔX (M Rp),Tambahan ΔNTB (M Rp),Rasio BA,Output Baru (M Rp),% Dampak,BL (Norm),FL (Norm),Klasifikasi',
        ];

        foreach ($hasil as $i => $row) {
            $lines[] = implode(',', [
                $i + 1,
                '"' . $row['nama'] . '"',
                number_format($row['output_awal'],            3, '.', ''),
                number_format($row['tambahan_output'],        3, '.', ''),
                number_format($row['tambahan_ntb']     ?? 0,  3, '.', ''),
                number_format($row['rasio_biaya_antara'] ?? 0, 4, '.', ''),
                number_format($row['output_baru'],            3, '.', ''),
                number_format($row['persen_dampak'],          2, '.', ''),
                number_format($row['norm_backward']    ?? 0,  4, '.', ''),
                number_format($row['norm_forward']     ?? 0,  4, '.', ''),
                $row['klasifikasi'] ?? '-',
            ]);
        }

        $filename = 'simulasi_io_' . date('Ymd_His') . '.csv';
        return response("\xEF\xBB\xBF" . implode("\n", $lines), 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportPdf()
    {
        $hasil        = session('hasil_simulasi');
        $summary      = session('summary_simulasi');
        $linkage_data = session('linkage_data', []);

        if (!$hasil || !$summary) return back()->with('error', 'Tidak ada data untuk diekspor.');

        return view('simulasi.export-pdf', compact('hasil', 'summary', 'linkage_data'));
    }

    // Export Riwayat
    public function exportExcelRiwayat($id)
    {
        $row = DB::table('simulasi_riwayat')
            ->where('id', $id)
            ->where('created_by', auth()->id())
            ->first();
        if (!$row) return back()->with('error', 'Riwayat tidak ditemukan.');

        $summary = json_decode($row->summary, true);
        $hasil   = json_decode($row->hasil,   true);

        $lines = [
            'HASIL SIMULASI INPUT-OUTPUT',
            'Nama Simulasi:,' . $row->nama_simulasi,
            'Dataset:,' . $summary['dataset'],
            'Tahun IO:,' . $summary['tahun'],
            'PDRB Referensi:,' . ($summary['pdrb_tahun'] ?? '-') . ' ' . ($summary['pdrb_triwulan'] ?? ''),
            '',
            '── SEKTOR YANG DIINJEKSI',
        ];

        if (!empty($summary['sektor_injeksi'])) {
            foreach ($summary['sektor_injeksi'] as $si) {
                $lines[] = '"' . $si['nama'] . '":,' . number_format($si['nilai'], 3, '.', '') . ' M Rp';
            }
        } else {
            $lines[] = 'Data sektor injeksi tidak tersedia';
        }

        $lines = array_merge($lines, [
            '',
            '── RINGKASAN EKONOMI',
            'Total Stimulus (Miliar Rp):,'         . number_format($summary['total_stimulus']    ?? 0, 3, '.', ''),
            'Tambahan Output ΔX (Miliar Rp):,'     . number_format($summary['tambahan_output']   ?? 0, 3, '.', ''),
            'Tambahan NTB ΔNTB (Miliar Rp):,'      . number_format($summary['tambahan_ntb']      ?? 0, 3, '.', ''),
            '',
            '── ANALISIS ADHB (Harga Berlaku)',
            'PDRB ADHB Awal (Miliar Rp):,'         . number_format($summary['pdrb_adhb_awal']    ?? $summary['pdrb_awal']  ?? 0, 3, '.', ''),
            'PDRB ADHB Baru Estimasi (Miliar Rp):,'. number_format($summary['pdrb_adhb_baru']    ?? $summary['pdrb_baru']  ?? 0, 3, '.', ''),
            'Dampak thd PDRB ADHB (%):,'           . number_format($summary['persen_naik_adhb']  ?? $summary['persen_naik'] ?? 0, 4, '.', ''),
            '',
            '── ANALISIS ADHK (Harga Konstan)',
            'PDRB ADHK Awal (Miliar Rp):,'         . number_format($summary['pdrb_adhk_awal']    ?? 0, 3, '.', ''),
            'PDRB ADHK Baru Estimasi (Miliar Rp):,'. number_format($summary['pdrb_adhk_baru']    ?? 0, 3, '.', ''),
            'Dampak thd PDRB ADHK (%):,'           . number_format($summary['persen_naik_adhk']  ?? 0, 4, '.', ''),
            '',
            '── MULTIPLIER',
            'Output Multiplier (ΔX/ΔY):,'          . number_format($summary['multiplier_output'] ?? $summary['multiplier'] ?? 0, 4, '.', ''),
            'NTB Multiplier (ΔNTB/ΔY):,'           . number_format($summary['multiplier_ntb']    ?? 0, 4, '.', ''),
            '',
            'No,Sektor,Output Awal (M Rp),Tambahan ΔX (M Rp),Tambahan ΔNTB (M Rp),Rasio BA,Output Baru (M Rp),% Dampak,BL (Norm),FL (Norm),Klasifikasi',
        ]);

        foreach ($hasil as $i => $r) {
            $lines[] = implode(',', [
                $i + 1,
                '"' . $r['nama'] . '"',
                number_format($r['output_awal'],             3, '.', ''),
                number_format($r['tambahan_output'],         3, '.', ''),
                number_format($r['tambahan_ntb']      ?? 0,  3, '.', ''),
                number_format($r['rasio_biaya_antara'] ?? 0,  4, '.', ''),
                number_format($r['output_baru'],             3, '.', ''),
                number_format($r['persen_dampak'],           2, '.', ''),
                number_format($r['norm_backward']     ?? 0,  4, '.', ''),
                number_format($r['norm_forward']      ?? 0,  4, '.', ''),
                $r['klasifikasi'] ?? '-',
            ]);
        }

        $filename = 'simulasi_io_' . Str::slug($row->nama_simulasi) . '_' . date('Ymd') . '.csv';
        return response("\xEF\xBB\xBF" . implode("\n", $lines), 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportPdfRiwayat($id)
    {
        $row = DB::table('simulasi_riwayat')
            ->where('id', $id)
            ->where('created_by', auth()->id())
            ->first();
        if (!$row) return back()->with('error', 'Riwayat tidak ditemukan.');

        $summary      = json_decode($row->summary, true);
        $hasil        = json_decode($row->hasil,   true);
        $linkage_data = array_map(fn($r) => [
            'nama'        => $r['nama'],
            'norm_backward' => $r['norm_backward'] ?? 0,
            'norm_forward'  => $r['norm_forward']  ?? 0,
            'klasifikasi'   => $r['klasifikasi']   ?? 'Independen',
        ], $hasil);

        return view('simulasi.export-pdf', compact('hasil', 'summary', 'linkage_data'));
    }

    // CHART DATA & HELPERS
    public function chartData()
    {
        return response()->json([
            'hasil'        => session('hasil_simulasi', []),
            'linkage_data' => session('linkage_data', []),
        ]);
    }

    public function reset()
    {
        session()->forget(['hasil_simulasi', 'summary_simulasi', 'simulasi_input', 'linkage_data']);
        return redirect()->route('simulasi.index')->with('success', 'Simulasi berhasil direset.');
    }

    public function detailSektor($id)
    {
        return response()->json(['id' => $id, 'nama' => 'Sektor ' . $id]);
    }

    public function listSektor()
    {
        $dataset = DB::table('io_datasets')->latest()->first();
        $data    = [];
        if ($dataset) {
            $sektors = DB::table('commodities')
                ->whereNull('parent_id')->orderBy('id')
                ->take($dataset->jumlah_sektor)->get();
            foreach ($sektors as $idx => $s) {
                $data[] = ['id' => $idx + 1, 'nama' => $s->nama];
            }
        }
        return response()->json($data);
    }

    // PRIVATE HELPERS
    private function getNamaSektor(): \Illuminate\Support\Collection
    {
        return DB::table('commodities')->whereNull('parent_id')
            ->orderBy('id')->pluck('nama')->values();
    }

    private function loadMatrixData(int $datasetId, int $n): array
    {
        $z = [];
        foreach (DB::table('io_transactions')->where('dataset_id', $datasetId)->get() as $t) {
            $z[$t->baris][$t->kolom] = (float) $t->nilai;
        }

        $x = [];
        foreach (DB::table('io_outputs')->where('dataset_id', $datasetId)->get() as $o) {
            $x[$o->sektor] = (float) $o->nilai;
        }

        for ($i = 1; $i <= $n; $i++) {
            if (!isset($x[$i]) || $x[$i] == 0) {
                return ["Output sektor ke-{$i} adalah nol atau tidak ada.", null];
            }
        }

        return [$z, $x];
    }

    private function buildLeontief(array $z, array $x, int $n): ?array
    {
        $B = [];
        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                $a = ($z[$i][$j] ?? 0) / $x[$j];
                $B[$i][$j] = ($i == $j) ? 1 - $a : -$a;
            }
        }
        return $this->matrixInverse($B, $n);
    }

    private function hitungLinkage(array $L, int $n): array
    {
        $backward = [];
        $forward  = [];
        $sumAll   = 0;

        for ($j = 1; $j <= $n; $j++) {
            $s = 0;
            for ($i = 1; $i <= $n; $i++) $s += $L[$i][$j];
            $backward[$j] = $s;
            $sumAll += $s;
        }
        for ($i = 1; $i <= $n; $i++) {
            $s = 0;
            for ($j = 1; $j <= $n; $j++) $s += $L[$i][$j];
            $forward[$i] = $s;
        }

        $avg         = $sumAll / $n;
        $normB       = [];
        $normF       = [];
        $klasifikasi = [];

        for ($i = 1; $i <= $n; $i++) {
            $nb = $backward[$i] / $avg;
            $nf = $forward[$i]  / $avg;
            $normB[$i]       = $nb;
            $normF[$i]       = $nf;
            $klasifikasi[$i] = match(true) {
                $nb >= 1 && $nf >= 1 => 'Kunci',
                $nb >= 1             => 'Hilir',
                $nf >= 1             => 'Hulu',
                default              => 'Independen',
            };
        }

        return [$backward, $forward, $normB, $normF, $klasifikasi];
    }

    private function getRasioPerSektor(int $n, ?int $tahun = null): array
    {
        $sektorIds = DB::table('commodities')
            ->whereNull('parent_id')
            ->orderBy('id')
            ->take($n)
            ->pluck('id')
            ->toArray();

        $rasio = [];

        foreach ($sektorIds as $idx => $sektorId) {
            $descendantIds = $this->getDescendantIds($sektorId);
            $allIds        = array_merge([$sektorId], $descendantIds);

            $query = DB::table('commodity_rasio')
                ->whereIn('commodity_id', $allIds);

            if ($tahun) {
                $query->where('tahun', '<=', $tahun);
            }

            $avg = $query->avg('rasio_biaya_antara');

            if ($avg === null) {
                // Fallback: tidak ada data rasio → asumsi 50%
                $rasio[$idx + 1] = 0.5;
            } else {
                $avg = (float) $avg;
                // Normalisasi: jika tersimpan sebagai persen
                if ($avg > 1) $avg = $avg / 100;
                // Guard: rasio harus dalam (0,1)
                $rasio[$idx + 1] = min(max($avg, 0.01), 0.99);
            }
        }

        return $rasio;
    }

    private function getDescendantIds(int $parentId): array
    {
        $ids      = [];
        $children = DB::table('commodities')
            ->where('parent_id', $parentId)
            ->pluck('id')
            ->toArray();

        foreach ($children as $childId) {
            $ids[] = $childId;
            $ids   = array_merge($ids, $this->getDescendantIds($childId));
        }

        return $ids;
    }

    private function getPdrbReferensi(): array
    {
        $periode = DB::table('pdrb_results')
            ->orderByDesc('tahun')
            ->orderByDesc('triwulan_id')
            ->select('tahun', 'triwulan_id')
            ->first();

        if (!$periode) return [0, 0, null];

        $baseQuery = DB::table('pdrb_results')
            ->where('tahun', $periode->tahun)
            ->where('triwulan_id', $periode->triwulan_id);

        $totalAdhb = (float) (clone $baseQuery)->sum('adhb');
        $totalAdhk = (float) (clone $baseQuery)->sum('adhk');

        $triwulan = DB::table('triwulanan')
            ->where('id', $periode->triwulan_id)
            ->first();

        $periode->triwulan_label = $triwulan->triwulan ?? 'TW ' . $periode->triwulan_id;

        return [$totalAdhb, $totalAdhk, $periode];
    }

    private function hitungSkenario(int|string $datasetId, array $sektors, array $nilais, string $label): array
    {
        $dataset = DB::table('io_datasets')->where('id', $datasetId)->first();
        if (!$dataset) return ['error' => 'Dataset tidak ditemukan.'];

        $n = $dataset->jumlah_sektor;

        [$z, $x] = $this->loadMatrixData($datasetId, $n);
        if (is_string($z)) return ['error' => $z];

        $L = $this->buildLeontief($z, $x, $n);
        if (!$L) return ['error' => 'Matrix singular.'];

        $rasioPerSektor = $this->getRasioPerSektor($n, $dataset->tahun ?? null);

        $Y = array_fill(1, $n, 0.0);
        foreach ($sektors as $k => $s) {
            $s = (int) $s;
            if ($s >= 1 && $s <= $n) $Y[$s] += (float) $nilais[$k];
        }

        $namaSektor         = $this->getNamaSektor();
        $hasil              = [];
        $totalTambahanOutput = 0;
        $totalTambahanNtb   = 0;
        $totalStimulus      = array_sum(array_map('floatval', $nilais));

        for ($i = 1; $i <= $n; $i++) {
            $tambahan    = 0;
            for ($j = 1; $j <= $n; $j++) $tambahan += $L[$i][$j] * $Y[$j];

            $rasio       = $rasioPerSektor[$i] ?? 0.5;
            $tambahanNtb = $tambahan * (1 - $rasio);
            $awal        = $x[$i];

            $hasil[] = [
                'sektor'             => $i,
                'nama'               => $namaSektor[$i - 1] ?? 'Sektor ' . $i,
                'output_awal'        => $awal,
                'tambahan_output'    => $tambahan,
                'tambahan_ntb'       => $tambahanNtb,
                'rasio_biaya_antara' => $rasio,
                'output_baru'        => $awal + $tambahan,
                'persen_dampak'      => $awal > 0 ? ($tambahan / $awal) * 100 : 0,
            ];

            $totalTambahanOutput += $tambahan;
            $totalTambahanNtb    += $tambahanNtb;
        }

        return [
            'hasil'   => $hasil,
            'summary' => [
                'label'              => $label,
                'dataset'            => $dataset->nama_dataset,
                'tahun'              => $dataset->tahun,
                'total_stimulus'     => $totalStimulus,
                'tambahan_output'    => $totalTambahanOutput,
                'tambahan_ntb'       => $totalTambahanNtb,
                'multiplier_output'  => $totalStimulus > 0
                    ? round($totalTambahanOutput / $totalStimulus, 4) : 0,
                'multiplier_ntb'     => $totalStimulus > 0
                    ? round($totalTambahanNtb / $totalStimulus, 4) : 0,
                // backward compat
                'multiplier'         => $totalStimulus > 0
                    ? round($totalTambahanOutput / $totalStimulus, 4) : 0,
            ],
        ];
    }

    private function matrixInverse(array $matrix, int $n): ?array
    {
        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                $matrix[$i][$j + $n] = ($i == $j) ? 1 : 0;
            }
        }

        for ($i = 1; $i <= $n; $i++) {
            $pivot = $matrix[$i][$i];
            if (abs($pivot) < 1e-12) return null;

            for ($j = 1; $j <= 2 * $n; $j++) $matrix[$i][$j] /= $pivot;

            for ($k = 1; $k <= $n; $k++) {
                if ($k == $i) continue;
                $f = $matrix[$k][$i];
                for ($j = 1; $j <= 2 * $n; $j++) $matrix[$k][$j] -= $f * $matrix[$i][$j];
            }
        }

        $inv = [];
        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                $inv[$i][$j] = $matrix[$i][$j + $n];
            }
        }
        return $inv;
    }
}