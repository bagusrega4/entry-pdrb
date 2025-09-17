<?php

namespace App\Http\Controllers;

use App\Models\Tim;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with('tim')->get(); // ambil semua jadwal dengan relasi tim
        $tims = Tim::all(); // ambil semua tim untuk dropdown

        return view('jadwal.index', compact('jadwals', 'tims'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tim_id' => 'required|exists:tims,id',
            'tanggal' => 'required|date',
            'sesi' => 'required',
            'jam' => 'required'
        ]);

        Jadwal::create([
            'tim_id' => $request->tim_id,
            'tanggal' => $request->tanggal,
            'sesi' => $request->sesi,
            'jam' => $request->jam,
        ]);

        return redirect()->route('form.index')->with('success', 'Jadwal berhasil ditambahkan');
    }
}
