<?php

namespace App\Http\Controllers;

use App\Models\BukuPanduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PanduanController extends Controller
{
    /**
     * Tampilkan halaman panduan
     */
    public function index()
    {
        // Ambil buku panduan terbaru berdasarkan created_at
        $bukuPanduanTerakhir = BukuPanduan::latest('created_at')->first();

        return view('panduan.index', compact('bukuPanduanTerakhir'));
    }

    /**
     * Tampilkan form upload panduan
     */
    public function uploadPanduan()
    {
        return view('panduan.upload', []);
    }

    /**
     * Proses upload / update file panduan
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:20480', // hanya PDF max 20MB
        ]);

        // Simpan file ke storage/app/public/panduan
        $path = $request->file('file')->store('panduan', 'public');

        // Simpan ke database
        BukuPanduan::create([
            'file' => $path,
        ]);

        return redirect()->route('panduan.index')->with('success', 'Buku panduan berhasil diperbarui.');
    }
}
