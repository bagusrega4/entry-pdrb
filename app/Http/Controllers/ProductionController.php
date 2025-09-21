<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commodity;
use App\Models\CommodityProduction;
use App\Models\IndicatorProduksi;
use App\Models\UnitProduksi;

class ProductionController extends Controller
{
    public function index()
    {
        $commodities = Commodity::whereNull('parent_id')->get();
        return view('productions.index', compact('commodities'));
    }

    public function getChildren($id)
    {
        return Commodity::where('parent_id', $id)->get();
    }

    public function getIndicators()
    {
        $indicators = IndicatorProduksi::select('id', 'indikator_produksi')->get();
        return response()->json($indicators);
    }

    public function getUnits()
    {
        $units = UnitProduksi::select('id', 'satuan_produksi')->get();
        return response()->json($units);
    }

    public function store(Request $request)
    {
        $request->validate([
            'commodity_id' => 'required|exists:commodities,id',
            'indicator_production_id' => 'nullable|exists:indicators_produksi,id',
            'unit_production_id'      => 'nullable|exists:units_produksi,id',
            'tahun'        => 'required|integer',
            'produksi'        => 'required|numeric',
        ]);

        CommodityProduction::create([
            'commodity_id' => $request->commodity_id,
            'indicator_production_id' => $request->indicator_production_id,
            'unit_production_id'      => $request->unit_production_id,
            'tahun'        => $request->tahun,
            'produksi'        => $request->produksi,
        ]);

        return response()->json(['success' => true]);
    }
}
