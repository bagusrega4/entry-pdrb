<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Tim;
use App\Models\ManageKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FormController extends Controller
{
    public function index()
    {
        $timList = Tim::orderBy('nama_tim')->get();

        // Hanya ambil kegiatan aktif
        $kegiatanList = ManageKegiatan::where('status', 'aktif')
            ->orderBy('nama_kegiatan')
            ->get();

        $user = auth()->user();

        return view('form.index', compact('timList', 'kegiatanList', 'user'));
    }

    public function create()
    {
        $timList = Tim::orderBy('nama_tim')->get();

        $kegiatanList = ManageKegiatan::where('status', 'aktif')
            ->orderBy('nama_kegiatan')
            ->get();

        $user = auth()->user();

        return view('form.create', compact('timList', 'kegiatanList', 'user'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'tim_id'      => 'required|exists:tims,id',
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_akhir'   => 'required|date_format:H:i|after:jam_mulai',
        ], [
            'kegiatan_id.exists' => 'Kegiatan yang dipilih tidak valid.',
        ]);

        // Simpan ke tabel forms
        $form = new Form();
        $form->user_id     = auth()->id();
        $form->tim_id      = $validated['tim_id'];
        $form->kegiatan_id = $validated['kegiatan_id'];
        $form->tanggal     = $validated['tanggal'];
        $form->jam_mulai   = $validated['jam_mulai'];
        $form->jam_akhir   = $validated['jam_akhir'];
        $form->save();

        // Data untuk pesan
        $pegawai   = $form->user->pegawai->nama ?? '-';
        $kegiatan  = $form->manageKegiatan->nama_kegiatan ?? '-';
        $deskripsi = $form->manageKegiatan->deskripsi ?? '-';

        // Cari ketua tim berdasarkan tim_id
        $ketua = \App\Models\User::where('tim_id', $form->tim_id)
            ->where('id_role', 2)
            ->first();

        if ($ketua && $ketua->no_hp) {
            // Ambil nama ketua dari tabel pegawai (relasi user -> pegawai)
            $namaKetua = $ketua->pegawai->nama ?? '-';
            $namaTim   = $form->tim->nama_tim ?? '-';

            $token = env('WHATSAPP_TOKEN');
            $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
            $url = "https://graph.facebook.com/v22.0/{$phoneNumberId}/messages";

            Http::withToken($token)->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $ketua->no_hp,
                'type' => 'text',
                'text' => [
                    'body' => "📢 *Notifikasi Rencana Keluar Dinas*\n\n"
                        . "Halo *{$namaKetua}*, berikut adalah data pegawai yang akan keluar untuk mengerjakan kegiatan dari tim *{$namaTim}*.\n\n"
                        . "👤 Nama Pegawai : {$pegawai}\n"
                        . "📅 Tanggal      : {$form->tanggal}\n"
                        . "⏰ Waktu        : {$form->jam_mulai} - {$form->jam_akhir}\n"
                        . "📌 Kegiatan     : {$kegiatan}\n"
                        . "📝 Deskripsi    : {$deskripsi}\n\n"
                        . "Mohon untuk dilakukan tindak lanjut, terima kasih 🙏\n\n"
                        . "*note:*\n"
                        . "*_untuk detailnya, kunjungi siremon.radata.id_\n"
                        . "**_pesan ini dikirimkan otomatis_"
                ]
            ]);
        }

        return redirect()->route('form.index')
            ->with('success', 'Rencana kerja berhasil ditambahkan dan pesan WA sudah dikirim ke ketua tim.');
    }

    // API untuk load kegiatan per tim
    public function getKegiatanByTim($timId)
    {
        $kegiatan = ManageKegiatan::where('tim_id', $timId)
            ->select('id', 'nama_kegiatan', 'deskripsi', 'periode_mulai', 'periode_selesai')
            ->get();

        return response()->json($kegiatan);
    }

    public function getKegiatan($tim_id)
    {
        // hanya ambil kegiatan yg aktif dan sesuai tim
        $kegiatan = \App\Models\ManageKegiatan::where('tim_id', $tim_id)
            ->where('status', 'aktif')
            ->orderBy('nama_kegiatan')
            ->get();

        return response()->json($kegiatan);
    }

    public function destroy($id)
    {
        $form = Form::findOrFail($id);

        if ($form->diketahui) {
            return redirect()->back()->with('error', 'Rencana kerja sudah diketahui ketua, tidak bisa dihapus.');
        }

        $form->delete();

        return redirect()->back()->with('success', 'Rencana kerja berhasil dihapus.');
    }
}
