<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Commodity;
use App\Models\CommodityWipCbr;
use App\Models\CommodityIhp;
use App\Models\CommodityPriceProduction;
use App\Models\CommodityRasio;
use App\Models\Indicator;
use App\Models\UnitHarga;
use App\Models\UnitProduksi;
use App\Models\Triwulan;
use App\Models\UnitPerawatan;
use App\Models\UnitLuas;
use App\Models\DownloadColumnConfig;
use Mpdf\Mpdf;

class DownloadController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data kategori (level 1) dari tabel commodities
        $kategoris = DB::table('commodities')
            ->where('level', 1)
            ->orderByRaw('CAST(kode AS UNSIGNED)')
            ->get();

        // Ambil data tahun dari tabel commodity_prices_productions
        $tahuns = DB::table('commodity_prices_productions')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Ambil data triwulan
        $triwulans = DB::table('triwulanan')->orderBy('id')->get();

        // Ambil konfigurasi kolom
        $columnConfigs = DownloadColumnConfig::ordered()->get();

        return view('download.index', compact('kategoris', 'tahuns', 'triwulans', 'columnConfigs'));
    }

    public function updateColumnConfig(Request $request)
    {
        try {
            $request->validate([
                'configs' => 'required|array',
                'configs.*.id' => 'required|exists:download_column_configs,id',
                'configs.*.is_visible' => 'required|boolean',
            ]);

            $updatedCount = 0;
            foreach ($request->configs as $config) {
                $updated = DownloadColumnConfig::where('id', $config['id'])
                    ->update(['is_visible' => $config['is_visible']]);
                if ($updated) {
                    $updatedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil memperbarui {$updatedCount} konfigurasi kolom"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generatePdf(Request $request)
    {
        // TAMBAHKAN INI DI AWAL: Tingkatkan limit untuk menangani HTML besar
        ini_set('pcre.backtrack_limit', '10000000'); // 10 juta
        ini_set('pcre.recursion_limit', '10000000');
        ini_set('memory_limit', '512M'); // Tingkatkan memory limit juga
        ini_set('max_execution_time', '300'); // 5 menit timeout

        try {
            $request->validate([
                'kategori_id' => 'required|string', // Ubah validasi untuk menerima 'all'
                'tahun' => 'required|integer',
                'triwulan_id' => 'required|exists:triwulanan,id',
            ]);

            $kategoriId = $request->kategori_id;
            $tahun = $request->tahun;
            $triwulanId = $request->triwulan_id;

            // Ambil data triwulan
            $triwulan = DB::table('triwulanan')
                ->where('id', $triwulanId)
                ->first();

            if (!$triwulan) {
                return redirect()->back()
                    ->with('error', 'Triwulan tidak ditemukan!')
                    ->withInput();
            }

            // Ambil konfigurasi kolom yang visible
            $visibleColumns = DownloadColumnConfig::visible()->ordered()->get();

            if ($visibleColumns->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Tidak ada kolom yang aktif! Silakan aktifkan minimal 1 kolom.')
                    ->withInput();
            }

            // Check apakah download semua kategori atau kategori spesifik
            if ($kategoriId === 'all') {
                return $this->generatePdfAllCategories($tahun, $triwulanId, $triwulan, $visibleColumns);
            } else {
                return $this->generatePdfSingleCategory($kategoriId, $tahun, $triwulanId, $triwulan, $visibleColumns);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function generatePdfSingleCategory($kategoriId, $tahun, $triwulanId, $triwulan, $visibleColumns)
    {
        // Ambil data kategori (level 1)
        $kategori = DB::table('commodities')
            ->where('id', $kategoriId)
            ->first();

        if (!$kategori) {
            return redirect()->back()
                ->with('error', 'Kategori tidak ditemukan!')
                ->withInput();
        }

        // Ambil semua child IDs dari kategori ini
        $allChildIds = $this->getAllChildIds($kategoriId);

        // Ambil semua commodities dalam hierarchy dengan sorting yang benar
        $commodities = DB::table('commodities')
            ->whereIn('id', $allChildIds)
            ->orderByRaw('CAST(SUBSTRING_INDEX(kode, ".", 1) AS UNSIGNED)')
            ->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode, ".", 2), ".", -1) AS UNSIGNED)')
            ->orderByRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(kode, ".", 3), ".", -1)')
            ->orderByRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(kode, ".", 4), ".", -1)')
            ->orderByRaw('SUBSTRING_INDEX(kode, ".", -1)')
            ->get();

        if ($commodities->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Tidak ada data komoditas untuk kategori yang dipilih!')
                ->withInput();
        }

        // Siapkan data dengan structure yang benar
        $data = $this->prepareDataForCategory($commodities, $tahun, $triwulanId);

        // Generate HTML dari view
        $html = view('download.pdf-template', [
            'kategori' => $kategori,
            'tahun' => $tahun,
            'triwulan' => $triwulan,
            'data' => $data,
            'visibleColumns' => $visibleColumns,
        ])->render();

        // Konfigurasi mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_header' => 0,
            'margin_footer' => 0,
            'default_font_size' => 8,
            'default_font' => 'dejavusans'
        ]);

        // Set PDF properties
        $mpdf->SetTitle('Data PDRB ' . $triwulan->triwulan . ' - ' . $tahun);
        $mpdf->SetAuthor('PDRB System');

        // Write HTML ke PDF
        $mpdf->WriteHTML($html);

        // Generate filename
        $filename = 'PDRB_' . str_replace(' ', '_', $kategori->nama) . '_TW' . $triwulan->triwulan . '_' . $tahun . '.pdf';

        // Output PDF
        return $mpdf->Output($filename, 'D');
    }

    private function generatePdfAllCategories($tahun, $triwulanId, $triwulan, $visibleColumns)
    {
        // Ambil semua kategori (level 1)
        $kategoris = DB::table('commodities')
            ->where('level', 1)
            ->orderByRaw('CAST(kode AS UNSIGNED)')
            ->get();

        if ($kategoris->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Tidak ada kategori ditemukan!')
                ->withInput();
        }

        // Konfigurasi mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_header' => 0,
            'margin_footer' => 0,
            'default_font_size' => 8,
            'default_font' => 'dejavusans'
        ]);

        // Set PDF properties
        $mpdf->SetTitle('Data PDRB Semua Kategori ' . $triwulan->triwulan . ' - ' . $tahun);
        $mpdf->SetAuthor('PDRB System');

        $isFirstPage = true;

        // Loop untuk setiap kategori
        foreach ($kategoris as $kategori) {
            // Tambah halaman baru kecuali untuk halaman pertama
            if (!$isFirstPage) {
                $mpdf->AddPage();
            } else {
                $isFirstPage = false;
            }

            // Ambil semua child IDs dari kategori ini
            $allChildIds = $this->getAllChildIds($kategori->id);

            // Ambil semua commodities dalam hierarchy dengan sorting yang benar
            $commodities = DB::table('commodities')
                ->whereIn('id', $allChildIds)
                ->orderByRaw('CAST(SUBSTRING_INDEX(kode, ".", 1) AS UNSIGNED)')
                ->orderByRaw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode, ".", 2), ".", -1) AS UNSIGNED)')
                ->orderByRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(kode, ".", 3), ".", -1)')
                ->orderByRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(kode, ".", 4), ".", -1)')
                ->orderByRaw('SUBSTRING_INDEX(kode, ".", -1)')
                ->get();

            // Skip jika tidak ada data
            if ($commodities->isEmpty()) {
                continue;
            }

            // Siapkan data dengan structure yang benar
            $data = $this->prepareDataForCategory($commodities, $tahun, $triwulanId);

            // Generate HTML dari view yang sudah ada (download.pdf-template)
            // dengan menambahkan flag showCategoryHeader
            $html = view('download.pdf-template', [
                'kategori' => $kategori,
                'tahun' => $tahun,
                'triwulan' => $triwulan,
                'data' => $data,
                'visibleColumns' => $visibleColumns,
                'showCategoryHeader' => true, // Tampilkan header kategori
            ])->render();

            // Write HTML ke PDF
            $mpdf->WriteHTML($html);
        }

        // Generate filename
        $filename = 'PDRB_Semua_Kategori_TW' . $triwulan->triwulan . '_' . $tahun . '.pdf';

        // Output PDF
        return $mpdf->Output($filename, 'D');
    }

    private function prepareDataForCategory($commodities, $tahun, $triwulanId)
    {
        $data = [];

        foreach ($commodities as $commodity) {
            // Ambil data prices & productions
            $priceProduction = DB::table('commodity_prices_productions')
                ->where('commodity_id', $commodity->id)
                ->where('tahun', $tahun)
                ->where('triwulan_id', $triwulanId)
                ->first();

            // Ambil data rasio dari tabel commodity_rasio
            $rasio = DB::table('commodity_rasio')
                ->where('commodity_id', $commodity->id)
                ->where('tahun', $tahun)
                ->where('triwulan_id', $triwulanId)
                ->first();

            // Ambil data WIP CBR dari tabel commodity_wip_cbr
            $wipCbr = DB::table('commodity_wip_cbr')
                ->where('commodity_id', $commodity->id)
                ->where('tahun', $tahun)
                ->where('triwulan_id', $triwulanId)
                ->first();

            // Ambil data IHP dari tabel commodity_ihp
            $ihp = DB::table('commodity_ihp')
                ->where('commodity_id', $commodity->id)
                ->where('tahun', $tahun)
                ->where('triwulan_id', $triwulanId)
                ->first();

            // Ambil data relasi
            $indikator = $commodity->indikator_id
                ? Indicator::find($commodity->indikator_id)
                : null;

            $satuanProduksi = $commodity->satuan_produksi_id
                ? UnitProduksi::find($commodity->satuan_produksi_id)
                : null;

            $satuanHarga = $commodity->satuan_harga_id
                ? UnitHarga::find($commodity->satuan_harga_id)
                : null;

            $satuanLuas = $commodity->satuan_luas_tanam_id
                ? UnitLuas::find($commodity->satuan_luas_tanam_id)
                : null;

            $satuanBiayaPerawatan = $commodity->satuan_biaya_perawatan_id
                ? UnitPerawatan::find($commodity->satuan_biaya_perawatan_id)
                : null;

            // Build hierarchy path untuk commodity ini
            $hierarchyPath = $this->buildHierarchyPath($commodity, $commodities);

            // Tambahkan ke data array dengan data tambahan
            $data[] = [
                'commodity' => $commodity,
                'indikator' => $indikator,
                'satuan_produksi' => $satuanProduksi,
                'satuan_harga' => $satuanHarga,
                'satuan_luas_tanam' => $satuanLuas,
                'satuan_biaya_perawatan' => $satuanBiayaPerawatan,
                'price_production' => $priceProduction,
                'rasio' => $rasio,
                'wip_cbr' => $wipCbr,
                'ihp' => $ihp,
                'hierarchy' => $hierarchyPath,
            ];
        }

        return $data;
    }

    private function getAllChildIds($parentId)
    {
        $ids = [$parentId];

        $children = DB::table('commodities')
            ->where('parent_id', $parentId)
            ->pluck('id');

        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->getAllChildIds($childId));
        }

        return $ids;
    }

    private function buildHierarchyPath($commodity, $allCommodities)
    {
        $path = [
            'level1' => null,
            'level2' => null,
            'level3' => null,
            'level4' => null,
            'level5' => null,
        ];

        $commoditiesMap = [];
        foreach ($allCommodities as $c) {
            $commoditiesMap[$c->id] = $c;
        }

        $current = $commodity;
        $levelKey = 'level' . $current->level;
        $path[$levelKey] = $current;

        while ($current->parent_id && isset($commoditiesMap[$current->parent_id])) {
            $current = $commoditiesMap[$current->parent_id];
            $levelKey = 'level' . $current->level;
            $path[$levelKey] = $current;
        }

        return $path;
    }
}
