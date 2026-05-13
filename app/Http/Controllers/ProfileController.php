<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\Pegawai;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $nip_lama = Auth::user()->nip_lama;
        $pegawai  = Pegawai::where('nip_lama', $nip_lama)->firstOrFail();

        return view('profile.index', [
            'user'    => $request->user(),
            'pegawai' => $pegawai,
        ]);
    }

    public function update(Request $request)
    {
        $user    = $request->user();
        $pegawai = Pegawai::where('nip_lama', $user->nip_lama)->firstOrFail();

        $validated = $request->validate([
            'nama'           => ['required', 'string', 'max:255'],
            'username'       => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email'          => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'jenis_kelamin'  => ['nullable', 'in:LK,PR'],
            'nip_baru'       => ['nullable', 'string', 'max:20'],
            'jabatan'        => ['nullable', 'string', 'max:255'],
            'golongan_akhir' => ['nullable', 'string', 'max:20'],
            'tamat_gol'      => ['nullable', 'date'],
            'pendidikan'     => ['nullable', 'string', 'max:50'],
            'tanggal_lulus'  => ['nullable', 'date'],
        ]);

        // Update users table
        $user->username = $validated['username'];
        $user->email    = $validated['email'];
        $user->save();

        $pegawaiData = ['nama' => $validated['nama'], 'jenis_kelamin' => $validated['jenis_kelamin'] ?? $pegawai->jenis_kelamin];

        if ($user->tim_id != 10) {
            $pegawaiData = array_merge($pegawaiData, [
                'nip_baru'       => $validated['nip_baru']       ?? $pegawai->nip_baru,
                'jabatan'        => $validated['jabatan']        ?? $pegawai->jabatan,
                'golongan_akhir' => $validated['golongan_akhir'] ?? $pegawai->golongan_akhir,
                'tamat_gol'      => $validated['tamat_gol']      ?? $pegawai->tamat_gol,
                'pendidikan'     => $validated['pendidikan']     ?? $pegawai->pendidikan,
                'tanggal_lulus'  => $validated['tanggal_lulus']  ?? $pegawai->tanggal_lulus,
            ]);
        }

        $pegawai->fill($pegawaiData)->save();

        return back()
            ->with('success', 'Data profil berhasil diperbarui.')
            ->with('activeTab', 'profile');
    }

    public function setPhotoProfile(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = $request->user();

        if ($user->photo && \Storage::disk('public')->exists($user->photo)) {
            \Storage::disk('public')->delete($user->photo);
        }

        $path = $request->file('photo')->store('photos', 'public');
        $user->photo = $path;
        $user->save();

        return back()
            ->with('success', 'Foto profil berhasil diperbarui.')
            ->with('activeTab', 'photo');
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validateWithBag('updatePassword', [
                'current_password' => ['required', 'current_password'],
                'password'         => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user           = $request->user();
            $user->password = Hash::make($request->password);
            $user->save();

            return back()
                ->with('success', 'Password berhasil diubah.')
                ->with('activeTab', 'password');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->validator, 'updatePassword')
                ->withInput()
                ->with('activeTab', 'password');
        }
    }

    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}