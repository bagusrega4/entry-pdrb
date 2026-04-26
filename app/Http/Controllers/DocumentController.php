<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Commodity;
use App\Models\Triwulan;
use App\Models\Document;

class DocumentController extends Controller
{
        public function index(Request $request)
        {
            $query = Document::with(['commodity', 'triwulan'])->latest();

            // SEARCH
            if ($request->search) {
                $query->where('nama', 'like', '%' . $request->search . '%');
            }

            // FILTER MODUL
            if ($request->modul) {
                $query->where('modul', $request->modul);
            }

            // FILTER TAHUN
            if ($request->tahun) {
                $query->where('tahun', $request->tahun);
            }

            // FILTER TRIWULAN
            if ($request->triwulan_id) {
                $query->where('triwulan_id', $request->triwulan_id);
            }

            // FILTER JENIS
            if ($request->jenis) {
                $query->where('jenis', $request->jenis);
            }

            $documents = $query->paginate(5)->withQueryString();

            $triwulans = Triwulan::all();

            return view('documents.index', compact('documents', 'triwulans'));
        }

        public function store(Request $request)
        {

            $request->validate([
                'nama' => 'required|string|max:255',
                'tahun' => 'required|numeric',
                'file' => 'required|file|max:5120',
                'commodity_id' => 'nullable|exists:commodities,id',
                'triwulan_id' => 'nullable|exists:triwulanan,id',
                'jenis' => 'nullable|string',
                'keterangan' => 'nullable|string',
            ]);

            $file = $request->file('file');

            $path = $file->store('documents', 'public');

            Document::create([
                'nama' => $request->nama,
                'tahun' => $request->tahun,
                'triwulan_id' => $request->triwulan_id,
                'jenis' => $request->jenis,
                'keterangan' => $request->keterangan,

                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),

                'modul' => $request->modul,
                'commodity_id' => $request->commodity_id,

                'uploaded_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil disimpan'
            ]);
        }

        public function update(Request $request, $id)
        {
            $request->validate([
                'nama' => 'required|string|max:255',
                'tahun' => 'nullable|numeric',
                'triwulan_id' => 'nullable|exists:triwulanan,id',
                'jenis' => 'nullable|string',
                'keterangan' => 'nullable|string',
            ]);

            $doc = Document::findOrFail($id);

            $doc->nama = $request->nama;
            $doc->tahun = $request->tahun;
            $doc->triwulan_id = $request->triwulan_id;
            $doc->jenis = $request->jenis;
            $doc->keterangan = $request->keterangan;

            $doc->save();

            return back()->with('success', 'Dokumen berhasil diupdate');
        }

        public function destroy($id)
        {
            $doc = Document::findOrFail($id);

            if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
                Storage::disk('public')->delete($doc->file_path);
            }

            $doc->delete();

            return back()->with('success', 'Dokumen berhasil dihapus');
        }

        public function show($id)
        {
            $doc = Document::findOrFail($id);

            $path = storage_path('app/public/' . $doc->file_path);

            if (!file_exists($path)) {
                return back()->with('error', 'File tidak ditemukan');
            }

            $extension = pathinfo($doc->file_path, PATHINFO_EXTENSION);
            $filename = $doc->nama . '.' . $extension;

            return response()->file($path, [
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]);
        }

        public function download($id)
        {
            $doc = Document::findOrFail($id);

            $path = storage_path('app/public/' . $doc->file_path);

            if (!file_exists($path)) {
                return back()->with('error', 'File tidak ditemukan');
            }

            return response()->download($path);
        }
}

