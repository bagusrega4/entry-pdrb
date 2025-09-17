<?php

namespace App\Http\Controllers;

use App\Models\ManageKegiatan;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use App\Models\Tim;
use App\Imports\KegiatanImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Exports\KegiatanExport;
use Barryvdh\DomPDF\Facade\Pdf;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // ambil semua input filter
        $statusFilter   = $request->get('status');
        $timFilter      = $request->get('tim_id');
        $namaFilter     = $request->get('nama_kegiatan');
        $periodeMulai   = $request->get('periode_mulai');
        $periodeSelesai = $request->get('periode_selesai');

        // base query
        if ($user->id_role == 3) {
            $query = ManageKegiatan::with('kegiatan', 'tim');
        } else {
            $query = ManageKegiatan::with('kegiatan', 'tim')
                ->where('tim_id', $user->tim_id);
        }

        // filter status
        if (!empty($statusFilter) && in_array($statusFilter, ['aktif', 'selesai'])) {
            $query->where('status', $statusFilter);
        }

        // filter nama kegiatan
        if (!empty($namaFilter)) {
            $query->where('nama_kegiatan', 'like', "%$namaFilter%");
        }

        // filter tim (hanya role 3)
        if ($user->id_role == 3 && !empty($timFilter)) {
            $query->where('tim_id', $timFilter);
        }

        // filter periode (overlap)
        if (!empty($periodeMulai) && !empty($periodeSelesai)) {
            $query->where(function ($q) use ($periodeMulai, $periodeSelesai) {
                $q->whereDate('periode_mulai', '<=', $periodeSelesai)
                    ->whereDate('periode_selesai', '>=', $periodeMulai);
            });
        } elseif (!empty($periodeMulai)) {
            // kalau hanya isi mulai → kegiatan yg selesai setelah mulai
            $query->whereDate('periode_selesai', '>=', $periodeMulai);
        } elseif (!empty($periodeSelesai)) {
            // kalau hanya isi selesai → kegiatan yg mulai sebelum selesai
            $query->whereDate('periode_mulai', '<=', $periodeSelesai);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // eksekusi query
        $kegiatanList = $query
            ->orderBy('id', 'desc')
            ->paginate($request->get('per_page', 5));

        // list tim untuk dropdown
        $timList = Tim::orderBy('nama_tim')->get();

        return view('manage.kegiatan.index', compact(
            'kegiatanList',
            'statusFilter',
            'timList'
        ));
    }

    public function create()
    {
        $kegiatan = Kegiatan::orderBy('nama_kegiatan')->get();
        return view('manage.kegiatan.create', compact('kegiatan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'periode_mulai'   => ['required', 'date', 'after_or_equal:today'],
            'periode_selesai' => ['required', 'date', 'after_or_equal:periode_mulai'],
            'deskripsi'       => ['nullable', 'string'],
            'kegiatan_id'     => ['nullable'],
        ]);

        $timId = auth()->user()->tim_id;

        if ($request->kegiatan_id === 'other') {
            $request->validate([
                'nama_kegiatan' => ['required', 'string', 'max:255'],
            ]);

            $kegiatanBaru = Kegiatan::create([
                'nama_kegiatan' => $request->nama_kegiatan,
            ]);

            ManageKegiatan::create([
                'kegiatan_id'     => $kegiatanBaru->id,
                'nama_kegiatan'   => $kegiatanBaru->nama_kegiatan,
                'deskripsi'       => $validated['deskripsi'] ?? null,
                'periode_mulai'   => $validated['periode_mulai'],
                'periode_selesai' => $validated['periode_selesai'],
                'status'          => 'aktif',
                'tim_id'          => $timId,
            ]);
        } else {
            $request->validate([
                'kegiatan_id' => ['required', 'exists:kegiatan,id'],
            ]);

            $kegiatan = Kegiatan::findOrFail($request->kegiatan_id);

            ManageKegiatan::create([
                'kegiatan_id'     => $kegiatan->id,
                'nama_kegiatan'   => $kegiatan->nama_kegiatan,
                'deskripsi'       => $validated['deskripsi'] ?? null,
                'periode_mulai'   => $validated['periode_mulai'],
                'periode_selesai' => $validated['periode_selesai'],
                'status'          => 'aktif',
                'tim_id'          => $timId,
            ]);
        }

        return redirect()
            ->route('manage.kegiatan.index')
            ->with('success', 'Kegiatan berhasil disimpan.');
    }

    public function selesai($id)
    {
        $kegiatan = ManageKegiatan::findOrFail($id);

        $kegiatan->update([
            'status' => 'selesai',
        ]);

        return redirect()
            ->route('manage.kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditandai selesai.');
    }

    public function aktif($id)
    {
        $kegiatan = ManageKegiatan::findOrFail($id);
        $kegiatan->status = 'aktif';
        $kegiatan->save();

        return redirect()->route('manage.kegiatan.index')->with('success', 'Kegiatan berhasil diaktifkan kembali.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new KegiatanImport, $request->file('file_excel'));

        return redirect()->route('manage.kegiatan.index')->with('success', 'Kegiatan berhasil diimport dari Excel!');
    }

    public function downloadTemplate()
    {
        $export = new class implements FromCollection {
            public function collection()
            {
                return new Collection([
                    ['nama_kegiatan', 'deskripsi', 'periode_mulai', 'periode_selesai'],
                    ['Contoh Kegiatan', 'Deskripsi contoh', '2025-08-01', '2025-08-10'],
                ]);
            }
        };

        return Excel::download($export, 'template_kegiatan.xlsx');
    }

    public function exportExcel(Request $request)
    {
        $user = auth()->user();

        $query = ($user->id_role == 3)
            ? ManageKegiatan::with('kegiatan', 'tim')
            : ManageKegiatan::with('kegiatan', 'tim')->where('tim_id', $user->tim_id);

        // filter status
        if ($request->filled('status') && in_array($request->status, ['aktif', 'selesai'])) {
            $query->where('status', $request->status);
        }

        // filter nama kegiatan
        if ($request->filled('nama_kegiatan')) {
            $query->where('nama_kegiatan', 'like', '%' . $request->nama_kegiatan . '%');
        }

        // filter tim
        if ($user->id_role == 3 && $request->filled('tim_id')) {
            $query->where('tim_id', $request->tim_id);
        }

        // filter periode
        if ($request->filled('periode_mulai') && $request->filled('periode_selesai')) {
            $query->where(function ($q) use ($request) {
                $q->whereDate('periode_mulai', '<=', $request->periode_selesai)
                    ->whereDate('periode_selesai', '>=', $request->periode_mulai);
            });
        } elseif ($request->filled('periode_mulai')) {
            $query->whereDate('periode_selesai', '>=', $request->periode_mulai);
        } elseif ($request->filled('periode_selesai')) {
            $query->whereDate('periode_mulai', '<=', $request->periode_selesai);
        }

        // urutkan per tim lalu nama kegiatan
        $kegiatanList = $query->orderBy('tim_id', 'asc')
            ->orderBy('nama_kegiatan', 'asc')
            ->get();

        return Excel::download(new KegiatanExport($kegiatanList), 'daftar_kegiatan.xlsx');
    }

    public function exportPDF(Request $request)
    {
        $user = auth()->user();

        $query = ($user->id_role == 3)
            ? ManageKegiatan::with('kegiatan', 'tim')
            : ManageKegiatan::with('kegiatan', 'tim')->where('tim_id', $user->tim_id);

        // filter status
        if ($request->filled('status') && in_array($request->status, ['aktif', 'selesai'])) {
            $query->where('status', $request->status);
        }

        // filter nama kegiatan
        if ($request->filled('nama_kegiatan')) {
            $query->where('nama_kegiatan', 'like', '%' . $request->nama_kegiatan . '%');
        }

        // filter tim
        if ($user->id_role == 3 && $request->filled('tim_id')) {
            $query->where('tim_id', $request->tim_id);
        }

        // filter periode
        if ($request->filled('periode_mulai') && $request->filled('periode_selesai')) {
            $query->where(function ($q) use ($request) {
                $q->whereDate('periode_mulai', '<=', $request->periode_selesai)
                    ->whereDate('periode_selesai', '>=', $request->periode_mulai);
            });
        } elseif ($request->filled('periode_mulai')) {
            $query->whereDate('periode_selesai', '>=', $request->periode_mulai);
        } elseif ($request->filled('periode_selesai')) {
            $query->whereDate('periode_mulai', '<=', $request->periode_selesai);
        }

        // urutkan per tim lalu nama kegiatan
        $kegiatanList = $query->orderBy('tim_id', 'asc')
            ->orderBy('nama_kegiatan', 'asc')
            ->get();

        // lempar ke blade PDF
        $pdf = Pdf::loadView('exports.kegiatan-pdf', compact('kegiatanList'));
        return $pdf->download('daftar_kegiatan.pdf');
    }

    public function destroy($id)
    {
        $manageKegiatan = ManageKegiatan::findOrFail($id);
        $manageKegiatan->delete();

        return redirect()->route('manage.kegiatan.index')
            ->with('success', 'Manage Kegiatan berhasil dihapus.');
    }
}
