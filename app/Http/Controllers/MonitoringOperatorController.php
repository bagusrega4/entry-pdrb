<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Tim;
use Illuminate\Http\Request;

class MonitoringOperatorController extends Controller
{
    public function upload(Request $request)
    {
        $user = auth()->user();

        $timList = $user->id_role == 3
            ? Tim::orderBy('nama_tim')->get()
            : collect();

        $query = Form::with(['manageKegiatan', 'user.pegawai'])
            ->orderBy('tanggal', 'desc');

        if ($user->id_role == 1) {
            $query->where('user_id', $user->id);
        } elseif ($user->id_role == 2) {
            $query->where(function ($q) use ($user) {
                $q->where('tim_id', $user->tim_id)
                    ->orWhere('user_id', $user->id);
            });
        }

        if ($request->filled('tim_id')) {
            $query->where('tim_id', $request->tim_id)
                ->orWhere('user_id', $user->id);
        }

        if ($request->filled('nama')) {
            $query->whereHas('user.pegawai', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->nama . '%');
            });
        }

        if ($request->filled('kegiatan')) {
            $query->whereHas('manageKegiatan', function ($q) use ($request) {
                $q->where('nama_kegiatan', 'like', '%' . $request->kegiatan . '%');
            });
        }

        if ($request->filled('periode_mulai') && $request->filled('periode_selesai')) {
            $query->whereHas('manageKegiatan', function ($q) use ($request) {
                $q->whereDate('periode_mulai', '<=', $request->periode_selesai)
                    ->whereDate('periode_selesai', '>=', $request->periode_mulai);
            });
        } elseif ($request->filled('periode_mulai')) {
            $query->whereHas('manageKegiatan', function ($q) use ($request) {
                $q->whereDate('periode_selesai', '>=', $request->periode_mulai);
            });
        } elseif ($request->filled('periode_selesai')) {
            $query->whereHas('manageKegiatan', function ($q) use ($request) {
                $q->whereDate('periode_mulai', '<=', $request->periode_selesai);
            });
        }

        $rencanaKerja = $query->paginate($request->get('per_page', 5))->withQueryString();

        return view('monitoring.operator.upload', compact('rencanaKerja', 'timList'));
    }

    public function updateStatus(Request $request, $id)
    {
        $form = Form::findOrFail($id);
        $user = auth()->user();

        $request->validate([
            'diketahui' => 'nullable|boolean'
        ]);

        if ($user->id_role == 3 || ($user->id_role == 2 && $form->tim_id == $user->tim_id)) {
            $form->diketahui = $request->boolean('diketahui') ? 1 : 0;
            $form->save();

            return back()->with('success', 'Status berhasil diperbarui.');
        }

        return back()->with('error', 'Anda tidak berhak mengubah status ini.');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_akhir'   => 'required',
        ]);

        Form::create([
            'user_id'     => $user->id,
            'tim_id'      => $user->tim_id,
            'kegiatan_id' => $request->kegiatan_id,
            'tanggal'     => $request->tanggal,
            'jam_mulai'   => $request->jam_mulai,
            'jam_akhir'   => $request->jam_akhir,
            'diketahui'   => 0
        ]);

        return back()->with('success', 'Form berhasil disimpan.');
    }

    public function updateLink(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:forms,id',
            'link_bukti' => 'required|url',
        ]);

        $form = Form::findOrFail($request->id);

        if ($form->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak berhak mengubah link ini!');
        }

        $form->link_bukti = $request->link_bukti;
        $form->save();

        return redirect()->back()->with('success', 'Link bukti dukung berhasil disimpan!');
    }
}
