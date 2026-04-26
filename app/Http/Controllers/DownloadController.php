<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Indicator;
use App\Models\UnitHarga;
use App\Models\UnitProduksi;
use App\Models\UnitPerawatan;
use App\Models\UnitLuas;
use App\Models\DownloadColumnConfig;
use Mpdf\Mpdf;

class DownloadController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = DB::table('commodities')
            ->where('level', 1)
            ->orderByRaw('CAST(kode AS UNSIGNED)')
            ->get();

        $tahuns = DB::table('commodity_prices_productions')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $triwulans    = DB::table('triwulanan')->orderBy('id')->get();
        $columnConfigs = DownloadColumnConfig::ordered()->get();

        return view('download.index', compact('kategoris', 'tahuns', 'triwulans', 'columnConfigs'));
    }

    // UPDATE COLUMN CONFIG
    public function updateColumnConfig(Request $request)
    {
        try {
            $request->validate([
                'configs'              => 'required|array',
                'configs.*.id'         => 'required|exists:download_column_configs,id',
                'configs.*.is_visible' => 'required|boolean',
            ]);

            $updatedCount = 0;
            foreach ($request->configs as $config) {
                $data = ['is_visible' => $config['is_visible']];
                if (isset($config['sort_order'])) {
                    $data['sort_order'] = (int) $config['sort_order'];
                }
                $updated = DownloadColumnConfig::where('id', $config['id'])->update($data);
                if ($updated) $updatedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil memperbarui {$updatedCount} konfigurasi kolom",
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // PREVIEW
    public function preview(Request $request)
    {
        try {
            $request->validate([
                'kategori_id' => 'required|string',
                'tahun'       => 'required|integer',
                'triwulan_id' => 'required|exists:triwulanan,id',
            ]);

            $kategoriId = $request->kategori_id;
            $tahun      = $request->tahun;
            $triwulanId = $request->triwulan_id;

            $triwulan = DB::table('triwulanan')->where('id', $triwulanId)->first();
            if (!$triwulan) return response()->json(['success' => false, 'message' => 'Triwulan tidak ditemukan!']);

            $visibleColumns = DownloadColumnConfig::visible()->ordered()->get();
            if ($visibleColumns->isEmpty()) return response()->json(['success' => false, 'message' => 'Tidak ada kolom aktif!']);

            $rootIds = $kategoriId === 'all'
                ? DB::table('commodities')->where('level', 1)->pluck('id')->toArray()
                : [$kategoriId];

            $allData = [];
            foreach ($rootIds as $rootId) {
                $commodities = $this->getCategoryCommodities($rootId);
                if ($commodities->isNotEmpty()) {
                    $allData = array_merge($allData, $this->prepareDataForCategory($commodities, $tahun, $triwulanId));
                }
            }

            $leafData = $this->resolveLeafData($allData);
            $total       = count($leafData);
            $previewRows = array_slice(array_values($leafData), 0, 50);

            $columns = $visibleColumns->pluck('column_label')->toArray();
            $rows    = [];
            foreach ($previewRows as $item) {
                $row = [];
                foreach ($visibleColumns as $col) {
                    $row[$col->column_label] = strip_tags(str_replace('<br>', ' › ', $this->renderCellValue($col->column_key, $item)));
                }
                $rows[] = $row;
            }

            $kategoriName = $kategoriId === 'all'
                ? 'Semua Kategori'
                : (DB::table('commodities')->where('id', $kategoriId)->value('nama') ?? '—');

            return response()->json([
                'success'       => true,
                'kategori_name' => $kategoriName,
                'tahun'         => $tahun,
                'triwulan_name' => $triwulan->triwulan,
                'columns'       => $columns,
                'rows'          => $rows,
                'total_rows'    => $total,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // GENERATE  (PDF / CSV)
    public function generatePdf(Request $request)
    {
        ini_set('pcre.backtrack_limit', '10000000');
        ini_set('pcre.recursion_limit', '10000000');
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        try {
            $request->validate([
                'kategori_id' => 'required|string',
                'tahun'       => 'required|integer',
                'triwulan_id' => 'required|exists:triwulanan,id',
                'format'      => 'nullable|in:pdf,csv',
            ]);

            $kategoriId  = $request->kategori_id;
            $tahun       = $request->tahun;
            $triwulanId  = $request->triwulan_id;
            $format      = $request->input('format', 'pdf');
            $columnOrder = $request->input('column_order', '');

            $triwulan = DB::table('triwulanan')->where('id', $triwulanId)->first();
            if (!$triwulan) return redirect()->back()->with('error', 'Triwulan tidak ditemukan!')->withInput();

            if (!empty($columnOrder)) $this->applyColumnOrder($columnOrder);

            $visibleColumns = DownloadColumnConfig::visible()->ordered()->get();
            if ($visibleColumns->isEmpty()) return redirect()->back()->with('error', 'Tidak ada kolom aktif!')->withInput();

            if ($format === 'csv') {
                return $kategoriId === 'all'
                    ? $this->generateCsvAllCategories($tahun, $triwulanId, $triwulan, $visibleColumns)
                    : $this->generateCsvSingleCategory($kategoriId, $tahun, $triwulanId, $triwulan, $visibleColumns);
            }

            return $kategoriId === 'all'
                ? $this->generatePdfAllCategories($tahun, $triwulanId, $triwulan, $visibleColumns)
                : $this->generatePdfSingleCategory($kategoriId, $tahun, $triwulanId, $triwulan, $visibleColumns);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    // PDF — SINGLE
    private function generatePdfSingleCategory($kategoriId, $tahun, $triwulanId, $triwulan, $visibleColumns)
    {
        $kategori = DB::table('commodities')->where('id', $kategoriId)->first();
        if (!$kategori) return redirect()->back()->with('error', 'Kategori tidak ditemukan!')->withInput();

        $commodities = $this->getCategoryCommodities($kategoriId);
        if ($commodities->isEmpty()) return redirect()->back()->with('error', 'Tidak ada data komoditas!')->withInput();

        $data = $this->prepareDataForCategory($commodities, $tahun, $triwulanId);
        $html = view('download.pdf-template', compact('kategori', 'tahun', 'triwulan', 'data', 'visibleColumns'))->render();

        $mpdf = $this->makeMpdf();
        $mpdf->SetTitle('Data PDRB ' . $triwulan->triwulan . ' - ' . $tahun);
        $mpdf->WriteHTML($html);

        $filename = 'PDRB_' . str_replace(' ', '_', $kategori->nama) . '_TW' . $triwulan->triwulan . '_' . $tahun . '.pdf';
        return $mpdf->Output($filename, 'D');
    }

    // PDF — ALL CATEGORIES
    private function generatePdfAllCategories($tahun, $triwulanId, $triwulan, $visibleColumns)
    {
        $kategoris = DB::table('commodities')->where('level', 1)->orderByRaw('CAST(kode AS UNSIGNED)')->get();
        if ($kategoris->isEmpty()) return redirect()->back()->with('error', 'Tidak ada kategori!')->withInput();

        $mpdf = $this->makeMpdf();
        $mpdf->SetTitle('Data PDRB Semua Kategori ' . $triwulan->triwulan . ' - ' . $tahun);

        $first = true;
        foreach ($kategoris as $kategori) {
            $commodities = $this->getCategoryCommodities($kategori->id);
            if ($commodities->isEmpty()) continue;
            if (!$first) $mpdf->AddPage();
            $first = false;
            $data = $this->prepareDataForCategory($commodities, $tahun, $triwulanId);
            $html = view('download.pdf-template', compact('kategori', 'tahun', 'triwulan', 'data', 'visibleColumns') + ['showCategoryHeader' => true])->render();
            $mpdf->WriteHTML($html);
        }

        $filename = 'PDRB_Semua_Kategori_TW' . $triwulan->triwulan . '_' . $tahun . '.pdf';
        return $mpdf->Output($filename, 'D');
    }

    // CSV — SINGLE CATEGORY
    private function generateCsvSingleCategory($kategoriId, $tahun, $triwulanId, $triwulan, $visibleColumns)
    {
        $kategori = DB::table('commodities')->where('id', $kategoriId)->first();
        if (!$kategori) return redirect()->back()->with('error', 'Kategori tidak ditemukan!')->withInput();

        $commodities = $this->getCategoryCommodities($kategoriId);
        if ($commodities->isEmpty()) return redirect()->back()->with('error', 'Tidak ada data komoditas!')->withInput();

        $data     = $this->prepareDataForCategory($commodities, $tahun, $triwulanId);
        $leafData = $this->resolveLeafData($data);
        $filename = 'PDRB_' . str_replace(' ', '_', $kategori->nama) . '_TW' . $triwulan->triwulan . '_' . $tahun . '.csv';

        return $this->streamCsv($leafData, $visibleColumns, $filename);
    }

    // CSV — ALL CATEGORIES
    private function generateCsvAllCategories($tahun, $triwulanId, $triwulan, $visibleColumns)
    {
        $kategoris = DB::table('commodities')->where('level', 1)->orderByRaw('CAST(kode AS UNSIGNED)')->get();
        if ($kategoris->isEmpty()) return redirect()->back()->with('error', 'Tidak ada kategori!')->withInput();

        $allLeafData = [];
        foreach ($kategoris as $kategori) {
            $commodities = $this->getCategoryCommodities($kategori->id);
            if ($commodities->isEmpty()) continue;
            $data        = $this->prepareDataForCategory($commodities, $tahun, $triwulanId);
            $allLeafData = array_merge($allLeafData, $this->resolveLeafData($data));
        }

        $filename = 'PDRB_Semua_Kategori_TW' . $triwulan->triwulan . '_' . $tahun . '.csv';
        return $this->streamCsv($allLeafData, $visibleColumns, $filename);
    }

    private function streamCsv(array $leafData, $visibleColumns, string $filename)
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        $out = fopen('php://output', 'w');

        fputs($out, "\xEF\xBB\xBF");

        // Baris header
        $headers = $visibleColumns->pluck('column_label')->toArray();
        fputcsv($out, $headers);

        // Baris data
        foreach ($leafData as $item) {
            $row = [];
            foreach ($visibleColumns as $col) {
                $raw = $this->renderCellValue($col->column_key, $item);
                // Bersihkan HTML: ganti <br> dengan spasi, strip tag lain
                $value = strip_tags(str_replace(['<br>', '<br/>', '<br />'], ' | ', $raw));
                $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $row[] = $value;
            }
            fputcsv($out, $row);
        }

        fclose($out);
        exit;
    }

    // HELPERS
    private function makeMpdf(): Mpdf
    {
        return new Mpdf([
            'mode'              => 'utf-8',
            'format'            => 'A4',
            'orientation'       => 'L',
            'margin_left'       => 10,
            'margin_right'      => 10,
            'margin_top'        => 15,
            'margin_bottom'     => 15,
            'margin_header'     => 0,
            'margin_footer'     => 0,
            'default_font_size' => 8,
            'default_font'      => 'dejavusans',
        ]);
    }

    private function applyColumnOrder(string $columnOrder)
    {
        $ids = array_filter(explode(',', $columnOrder));
        foreach ($ids as $index => $id) {
            DownloadColumnConfig::where('id', (int) $id)->update(['sort_order' => $index + 1]);
        }
    }

    private function getCategoryCommodities($kategoriId)
    {
        $allChildIds = $this->getAllChildIds($kategoriId);
        return DB::table('commodities')
            ->whereIn('id', $allChildIds)
            ->orderByRaw('CAST(SUBSTRING_INDEX(kode, ".", 1) AS UNSIGNED)')
            ->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode, ".", 2), ".", -1) AS UNSIGNED)')
            ->orderByRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(kode, ".", 3), ".", -1)')
            ->orderByRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(kode, ".", 4), ".", -1)')
            ->orderByRaw('SUBSTRING_INDEX(kode, ".", -1)')
            ->get();
    }

    private function resolveLeafData(array $data): array
    {
        $leafData = array_filter($data, function ($item) use ($data) {
            foreach ($data as $check) {
                if ($check['commodity']->parent_id == $item['commodity']->id) return false;
            }
            return true;
        });

        if (empty($leafData) && !empty($data)) {
            $maxLevel = max(array_map(fn($i) => $i['commodity']->level, $data));
            $leafData = array_filter($data, fn($i) => $i['commodity']->level == $maxLevel);
        }

        return array_values($leafData);
    }

    private function prepareDataForCategory($commodities, $tahun, $triwulanId): array
    {
        $data = [];
        foreach ($commodities as $commodity) {
            $priceProduction = DB::table('commodity_prices_productions')
                ->where('commodity_id', $commodity->id)->where('tahun', $tahun)->where('triwulan_id', $triwulanId)->first();
            $rasio = DB::table('commodity_rasio')
                ->where('commodity_id', $commodity->id)->where('tahun', $tahun)->where('triwulan_id', $triwulanId)->first();
            $wipCbr = DB::table('commodity_wip_cbr')
                ->where('commodity_id', $commodity->id)->where('tahun', $tahun)->where('triwulan_id', $triwulanId)->first();
            $ihp = DB::table('commodity_ihp')
                ->where('commodity_id', $commodity->id)->where('tahun', $tahun)->where('triwulan_id', $triwulanId)->first();

            $data[] = [
                'commodity'              => $commodity,
                'indikator'              => $commodity->indikator_id              ? Indicator::find($commodity->indikator_id)              : null,
                'satuan_produksi'        => $commodity->satuan_produksi_id        ? UnitProduksi::find($commodity->satuan_produksi_id)      : null,
                'satuan_harga'           => $commodity->satuan_harga_id           ? UnitHarga::find($commodity->satuan_harga_id)            : null,
                'satuan_luas_tanam'      => $commodity->satuan_luas_tanam_id      ? UnitLuas::find($commodity->satuan_luas_tanam_id)        : null,
                'satuan_biaya_perawatan' => $commodity->satuan_biaya_perawatan_id ? UnitPerawatan::find($commodity->satuan_biaya_perawatan_id) : null,
                'price_production'       => $priceProduction,
                'rasio'                  => $rasio,
                'wip_cbr'                => $wipCbr,
                'ihp'                    => $ihp,
                'hierarchy'              => $this->buildHierarchyPath($commodity, $commodities),
            ];
        }
        return $data;
    }

    private function renderCellValue(string $columnKey, array $item): string
    {
        $commodity       = $item['commodity'];
        $priceProduction = $item['price_production'] ?? null;
        $rasio           = $item['rasio']   ?? null;
        $wipCbr          = $item['wip_cbr'] ?? null;
        $ihp             = $item['ihp']     ?? null;
        $h               = $item['hierarchy'];
        $level1          = $h['level1'] ?? null;
        $level2          = $h['level2'] ?? null;
        $level3          = $h['level3'] ?? null;
        $level4          = $h['level4'] ?? null;

        $fmt = function ($val, $decimals = 2) {
            if ($val === null || $val === '') return '—';
            $f = number_format((float) $val, $decimals, ',', '.');
            return rtrim(rtrim($f, '0'), ',');
        };

        return match ($columnKey) {
            'kategori'               => $level1 ? $level1->kode . '<br>' . $level1->nama : '—',
            'sub_kategori_1'         => $level2 ? $level2->kode . '<br>' . $level2->nama : '—',
            'sub_kategori_2'         => $level3 ? $level3->kode . '<br>' . $level3->nama : '—',
            'sub_kategori_3'         => $level4 ? $level4->kode . '<br>' . $level4->nama : '—',
            'nama_komoditas'         => $commodity->nama ?? '—',
            'indikator'              => $item['indikator']?->indikator              ?? '—',
            'satuan_produksi'        => $item['satuan_produksi']?->satuan_produksi  ?? '—',
            'satuan_harga'           => $item['satuan_harga']?->satuan_harga        ?? '—',
            'satuan_luas'            => $item['satuan_luas_tanam']?->satuan_luas_tanam ?? '—',
            'satuan_biaya_perawatan' => $item['satuan_biaya_perawatan']?->satuan_biaya_perawatan ?? '—',
            'produksi'               => $priceProduction ? $fmt($priceProduction->produksi ?? null) : '—',
            'harga'                  => $priceProduction ? $fmt($priceProduction->harga    ?? null) : '—',
            'rasio_output_ikutan'    => $rasio    ? $fmt($rasio->rasio_output_ikutan  ?? null) : '—',
            'rasio_wip_cbr'          => $rasio    ? $fmt($rasio->rasio_wip_cbr        ?? null) : '—',
            'rasio_biaya_antara'     => $rasio    ? $fmt($rasio->rasio_biaya_antara   ?? null) : '—',
            'ihp'                    => $ihp      ? $fmt($ihp->ihp                   ?? null) : '—',
            'luas_tanam'             => $wipCbr   ? $fmt($wipCbr->luas_tanam_akhir_tahun ?? null) : '—',
            'biaya_perawatan'        => $wipCbr   ? $fmt($wipCbr->biaya_perawatan    ?? null) : '—',
            default                  => '—',
        };
    }

    private function getAllChildIds($parentId): array
    {
        $ids      = [$parentId];
        $children = DB::table('commodities')->where('parent_id', $parentId)->pluck('id');
        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->getAllChildIds($childId));
        }
        return $ids;
    }

    private function buildHierarchyPath($commodity, $allCommodities): array
    {
        $path = ['level1' => null, 'level2' => null, 'level3' => null, 'level4' => null, 'level5' => null];
        $map  = [];
        foreach ($allCommodities as $c) $map[$c->id] = $c;

        $current = $commodity;
        $path['level' . $current->level] = $current;

        while ($current->parent_id && isset($map[$current->parent_id])) {
            $current = $map[$current->parent_id];
            $path['level' . $current->level] = $current;
        }
        return $path;
    }
}