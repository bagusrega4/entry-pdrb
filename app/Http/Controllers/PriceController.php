<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commodity;
use App\Models\CommodityPrice;
use App\Models\IndicatorHarga;
use App\Models\UnitHarga;

class PriceController extends Controller
{
    public function index()
    {
        $commodities = Commodity::whereNull('parent_id')->get();
        return view('prices.index', compact('commodities'));
    }

    public function getChildren($id)
    {
        return Commodity::where('parent_id', $id)->get();
    }

    public function getIndicators()
    {
        $indicators = IndicatorHarga::select('id', 'indikator_harga')->get();
        return response()->json($indicators);
    }

    public function getUnits()
    {
        $units = UnitHarga::select('id', 'satuan_harga')->get();
        return response()->json($units);
    }

    public function store(Request $request)
    {
        $request->validate([
            'commodity_id' => 'required|exists:commodities,id',
            'indicator_price_id' => 'nullable|exists:indicators_harga,id',
            'unit_price_id'      => 'nullable|exists:units_harga,id',
            'tahun'        => 'required|integer',
            'harga'        => 'required|numeric',
        ]);

        CommodityPrice::create([
            'commodity_id' => $request->commodity_id,
            'indicator_price_id' => $request->indicator_price_id,
            'unit_price_id'      => $request->unit_price_id,
            'tahun'        => $request->tahun,
            'harga'        => $request->harga,
        ]);

        return response()->json(['success' => true]);
    }
}
