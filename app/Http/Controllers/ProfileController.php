<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\Pegawai;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $nip_lama = Auth::user()->nip_lama;
        $pegawai = Pegawai::where('nip_lama', $nip_lama)->firstOrFail();

        return view('profile.edit', [
            'user' => $request->user(),
            'pegawai' => $pegawai,
        ]);
    }


    /**
     * Change the user's account password.
     */
    public function changePassword(Request $request)
    {
        try {
            $request->validateWithBag('updatePassword', [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user = $request->user();
            $user->password = Hash::make($request->password);
            $user->save();

            return back()->with('success', 'Password Anda berhasil diubah')->with('activeTab', 'password');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator, 'updatePassword')
                ->withInput()
                ->with('activeTab', 'password');
        }
    }
}
