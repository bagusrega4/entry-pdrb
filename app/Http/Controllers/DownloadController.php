<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\Indicator;
use App\Models\UnitProduksi;

class DownloadController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data kategori (level 1) dari tabel commodities
        $kategoris = DB::table('commodities')
            ->where('level', 1)
            ->orderBy('kode')
            ->get();

        // Ambil data tahun dari tabel commodity_prices_productions
        $tahuns = DB::table('commodity_prices_productions')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Ambil data triwulan
        $triwulans = DB::table('triwulanan')->orderBy('id')->get();

        return view('download.index', compact('kategoris', 'tahuns', 'triwulans'));
    }

    public function generatePdf(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:commodities,id',
            'tahun' => 'required|integer',
            'triwulan_id' => 'required|exists:triwulanan,id',
        ]);

        $kategoriId = $request->kategori_id;
        $tahun = $request->tahun;
        $triwulanId = $request->triwulan_id;

        // Ambil data kategori (level 1)
        $kategori = DB::table('commodities')
            ->where('id', $kategoriId)
            ->first();

        // Ambil data triwulan
        $triwulan = DB::table('triwulanan')
            ->where('id', $triwulanId)
            ->first();

        // Ambil semua child IDs dari kategori ini
        $allChildIds = $this->getAllChildIds($kategoriId);

        // Ambil semua commodities dalam hierarchy
        $commodities = DB::table('commodities')
            ->whereIn('id', $allChildIds)
            ->orderBy('level')
            ->orderBy('kode')
            ->get();

        // Siapkan data dengan structure yang benar
        $data = [];

        foreach ($commodities as $commodity) {
            // Ambil data prices & productions
            $priceProduction = DB::table('commodity_prices_productions')
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

            // Build hierarchy path untuk commodity ini
            $hierarchyPath = $this->buildHierarchyPath($commodity, $commodities);

            // Tambahkan ke data array
            $data[] = [
                'commodity' => $commodity,
                'indikator' => $indikator,
                'satuan_produksi' => $satuanProduksi,
                'price_production' => $priceProduction,
                'hierarchy' => $hierarchyPath,
            ];
        }

        // Generate PDF
        $pdf = Pdf::loadView('download.pdf-template', [
            'kategori' => $kategori,
            'tahun' => $tahun,
            'triwulan' => $triwulan,
            'data' => $data,
        ]);

        // Set paper size dan orientation
        $pdf->setPaper('a4', 'landscape');

        // Download PDF
        $filename = 'PDRB_' . str_replace(' ', '_', $kategori->nama) . '_TW' . $triwulan->triwulan . '_' . $tahun . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Rekursif untuk mendapatkan semua child ID dari suatu kategori
     */
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

    /**
     * Build hierarchy path untuk commodity
     * Return array dengan level1, level2, level3, level4, level5
     */
    private function buildHierarchyPath($commodity, $allCommodities)
    {
        $path = [
            'level1' => null,
            'level2' => null,
            'level3' => null,
            'level4' => null,
            'level5' => null,
        ];

        // Map commodities by ID untuk lookup cepat
        $commoditiesMap = [];
        foreach ($allCommodities as $c) {
            $commoditiesMap[$c->id] = $c;
        }

        // Traverse ke atas untuk build path
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