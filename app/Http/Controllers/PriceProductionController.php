<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commodity;
use App\Models\CommodityPriceProduction;
use App\Models\Indicator;
use App\Models\UnitHarga;
use App\Models\UnitProduksi;

class PriceProductionController extends Controller
{
    public function index()
    {
        // Ambil semua komoditas root (tanpa parent)
        $commodities = Commodity::whereNull('parent_id')
            ->with(['childrenRecursive', 'pricesProduction'])
            ->get();

        return view('prices_productions.index', compact('commodities'));
    }

    public function getChildren(Commodity $commodity)
    {
        $commodity->load(['childrenRecursive', 'pricesProduction']);
        return response()->json($commodity->children);
    }

    public function getIndicators()
    {
        $indicators = Indicator::select('id', 'indikator')->get();
        return response()->json($indicators);
    }

    public function getUnitHarga()
    {
        $units = UnitHarga::select('id', 'satuan_harga')->get();
        return response()->json($units);
    }
    public function getUnitProduksi()
    {
        $units = UnitProduksi::select('id', 'satuan_produksi')->get();
        return response()->json($units);
    }

    public function getSubtree(Commodity $commodity)
    {
        $commodity->load([
            'childrenRecursive',
            'pricesProduction',
            'indicator',
            'unitHarga',
            'unitProduksi'
        ]);

        $flatten = $this->flattenCommodity($commodity);

        $years = CommodityPriceProduction::whereIn('commodity_id', collect($flatten)->pluck('id'))
            ->pluck('tahun')->unique()->sort()->values();

        return response()->json([
            'commodities' => $flatten,
            'years' => $years
        ]);
    }

    private function flattenCommodity($commodity, $prefix = '', $isRoot = true)
    {
        $rows = [];

        $name = trim(($prefix ? $prefix . ' ' : '') . $commodity->kode . ' - ' . $commodity->nama);

        // ambil harga & produksi (dari tabel commodity_price_productions)
        $prices = [];
        $productions = [];
        foreach ($commodity->pricesProduction as $p) {
            $prices[$p->tahun] = $p->harga;
            $productions[$p->tahun] = $p->produksi ?? null;
        }

        $isParent = $commodity->children->count() > 0;

        $rows[] = [
            'id' => $commodity->id,
            'kode' => $commodity->kode,
            'nama' => $commodity->nama,
            'full_name' => $name,
            'is_parent' => $isParent,
            'is_leaf' => !$isParent,
            'prices' => $prices,
            'productions' => $productions,

            // indikator & satuan ambil langsung dari tabel commodities
            'indikator_id' => $commodity->indikator_id,
            'satuan_harga_id' => $commodity->satuan_harga_id,
            'satuan_produksi_id' => $commodity->satuan_produksi_id,

            // nama relasi juga langsung dari commodities
            'indicator_name' => $commodity->indicator ? $commodity->indicator->indikator : null,
            'satuan_harga_name' => $commodity->unitHarga ? $commodity->unitHarga->satuan_harga : null,
            'satuan_produksi_name' => $commodity->unitProduksi ? $commodity->unitProduksi->satuan_produksi : null,
        ];

        // recursive untuk anak
        foreach ($commodity->children as $child) {
            $rows = array_merge($rows, $this->flattenCommodity($child, $name, false));
        }

        return $rows;
    }

    public function generateNextCode(Request $request, Commodity $parent = null)
    {
        // Kalau parent kosong (mau buat root baru)
        if (!$parent) {
            $roots = Commodity::whereNull('parent_id')->pluck('kode');

            if ($roots->isEmpty()) {
                return response()->json(['new_code' => '1']);
            }

            $max = $roots->map(function ($kode) {
                $parts = explode('.', $kode);
                $last = preg_replace('/\D/', '', end($parts));
                return $last === '' ? 0 : intval($last);
            })->max();

            return response()->json(['new_code' => strval($max + 1)]);
        }

        // Kalau ada parent → generate child
        $parentKode = trim($parent->kode);
        $parentDots = substr_count($parentKode, '.');

        $children = Commodity::where('kode', 'like', $parentKode . '.%')->pluck('kode');

        $direct = $children->filter(function ($kode) use ($parentDots) {
            return substr_count($kode, '.') === ($parentDots + 1);
        });

        if ($direct->isEmpty()) {
            return response()->json(['new_code' => $parentKode . '.1']);
        }

        $max = $direct->map(function ($kode) {
            $parts = explode('.', $kode);
            $last = preg_replace('/\D/', '', array_pop($parts));
            return $last === '' ? 0 : intval($last);
        })->max();

        return response()->json(['new_code' => $parentKode . '.' . ($max + 1)]);
    }

    public function storeCommodity(Request $request)
    {
        $validated = $request->validate([
            'parent_id'           => 'nullable|exists:commodities,id',
            'kode'                => 'required|string|max:50|unique:commodities,kode',
            'nama'                => 'required|string|max:255',
            'indikator_id'        => 'nullable|exists:indicators,id',
            'satuan_harga_id'     => 'nullable|exists:units_harga,id',
            'satuan_produksi_id'  => 'nullable|exists:units_produksi,id',
        ]);

        $level = 0;
        if (!empty($validated['parent_id'])) {
            $level = Commodity::find($validated['parent_id'])->level + 1;
        }

        $commodity = Commodity::create([
            'parent_id'          => $validated['parent_id'] ?? null,
            'kode'               => $validated['kode'],
            'nama'               => $validated['nama'],
            'indikator_id'       => $validated['indikator_id'] ?? null,
            'satuan_harga_id'    => $validated['satuan_harga_id'] ?? null,
            'satuan_produksi_id' => $validated['satuan_produksi_id'] ?? null,
            'level'              => $level,
        ]);

        return response()->json($commodity);
    }

    public function getAllCommodities()
    {
        $commodities = Commodity::select('id', 'kode', 'nama', 'parent_id')
            ->orderBy('kode')
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'full_name' => $c->kode . ' - ' . $c->nama
                ];
            });

        return response()->json($commodities);
    }

    public function storeIndicator(Request $request)
    {
        $validated = $request->validate([
            'indikator' => 'required|string|max:255',
        ]);

        $indikator = Indicator::create($validated);

        return response()->json($indikator);
    }

    public function storeUnitHarga(Request $request)
    {
        $validated = $request->validate([
            'satuan_harga' => 'required|string|max:255',
        ]);

        $unit = UnitHarga::create($validated);

        return response()->json($unit);
    }

    public function storeUnitProduksi(Request $request)
    {
        $validated = $request->validate([
            'satuan_produksi' => 'required|string|max:255',
        ]);

        $unit = UnitProduksi::create($validated);

        return response()->json($unit);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'commodity_id'       => 'required|exists:commodities,id',
            'tahun'              => 'required|integer',
            'harga'              => 'required|numeric',
            'produksi'           => 'nullable|numeric',
        ]);

        CommodityPriceProduction::updateOrCreate(
            [
                'commodity_id'       => $validated['commodity_id'],
                'tahun'              => $validated['tahun'],
            ],
            [
                'harga'              => $validated['harga'],
                'produksi'           => $validated['produksi'] ?? null,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function bulkStore(Request $request)
    {
        $data = json_decode($request->input('data'), true);

        foreach ($data as $row) {
            $commodity = Commodity::find($row['commodity_id']);
            if (!$commodity) {
                continue;
            }

            CommodityPriceProduction::updateOrCreate(
                [
                    'commodity_id' => $row['commodity_id'],
                    'tahun'        => $row['tahun'],
                ],
                [
                    'harga'              => $row['harga'] ?? null,
                    'produksi'           => $row['produksi'] ?? null,
                ]
            );
        }

        return response()->json(['success' => true]);
    }
}
