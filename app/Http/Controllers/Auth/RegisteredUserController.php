<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Tim;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    // Tampilkan form registrasi.
    public function create(): View
    {
        $tims = Tim::orderBy('nama_tim')->get();
        return view('auth.register', compact('tims'));
    }

    // Proses registrasi.
    public function store(Request $request): RedirectResponse
    {
        $isBps = $request->input('is_bps') === '1';

        // VALIDASI
        $commonRules = [
            'is_bps'   => ['required', 'in:0,1'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'photo'    => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];

        $commonMessages = [
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
            'photo.required'    => 'Foto profil wajib diunggah.',
            'photo.image'       => 'File harus berupa gambar.',
            'photo.mimes'       => 'Format foto harus JPG atau PNG.',
            'photo.max'         => 'Ukuran foto maksimal 2MB.',
        ];

        if ($isBps) {
            $specificRules = [
                'nip_lama'      => ['required', 'string', 'max:50'],
                'nip_baru'      => ['required', 'string', 'max:50'],
                'nama'          => ['required', 'string', 'max:255'],
                'jabatan'       => ['required', 'string', 'max:255'],
                'golongan_akhir'=> ['required', 'string', 'max:10'],
                'tamat_gol'     => ['required', 'date'],
                'pendidikan'    => ['required', 'string', 'max:20'],
                'tanggal_lulus' => ['required', 'date'],
                'jenis_kelamin' => ['required', 'in:LK,PR'],
                'tim_id'        => ['required', 'exists:tims,id'],
                'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            ];
            $specificMessages = [
                'nip_lama.required'       => 'NIP Lama wajib diisi.',
                'nip_baru.required'       => 'NIP Baru wajib diisi.',
                'nama.required'           => 'Nama lengkap wajib diisi.',
                'jabatan.required'        => 'Jabatan wajib diisi.',
                'golongan_akhir.required' => 'Golongan akhir wajib diisi.',
                'tamat_gol.required'      => 'Tamat golongan wajib diisi.',
                'tamat_gol.date'          => 'Format tanggal tamat golongan tidak valid.',
                'pendidikan.required'     => 'Pendidikan wajib dipilih.',
                'tanggal_lulus.required'  => 'Tanggal lulus wajib diisi.',
                'tanggal_lulus.date'      => 'Format tanggal lulus tidak valid.',
                'jenis_kelamin.required'  => 'Jenis kelamin wajib dipilih.',
                'tim_id.required'         => 'Tim wajib dipilih.',
                'tim_id.exists'           => 'Tim yang dipilih tidak valid.',
                'email.required'          => 'Email wajib diisi.',
                'email.unique'            => 'Email sudah terdaftar.',
                'email.email'             => 'Format email tidak valid.',
            ];
        } else {
            $specificRules = [
                'nama_nonbps'         => ['required', 'string', 'max:255'],
                'jenis_kelamin_nonbps'=> ['required', 'in:LK,PR'],
                'email_nonbps'        => ['required', 'string', 'email', 'max:255', 'unique:users,email', 'unique:pegawai,email'],
            ];
            $specificMessages = [
                'nama_nonbps.required'          => 'Nama lengkap wajib diisi.',
                'jenis_kelamin_nonbps.required' => 'Jenis kelamin wajib dipilih.',
                'email_nonbps.required'         => 'Email wajib diisi.',
                'email_nonbps.unique'           => 'Email sudah terdaftar.',
                'email_nonbps.email'            => 'Format email tidak valid.',
            ];
        }

        $request->validate(
            array_merge($commonRules, $specificRules),
            array_merge($commonMessages, $specificMessages)
        );

        if ($isBps) {
            $nipSudahAda = User::where('nip_lama', $request->nip_lama)->exists();
            if ($nipSudahAda) {
                return back()
                    ->withInput()
                    ->with('nip_exists', $request->nip_lama);
            }
        }

        // UPLOAD FOTO
        $photoPath = $request->file('photo')->store('photos/users', 'public');

        // PROSES BERDASARKAN STATUS
        if ($isBps) {

            $pegawai = Pegawai::updateOrCreate(
                ['nip_lama' => $request->nip_lama],
                [
                    'nama'           => $request->nama,
                    'nip_baru'       => $request->nip_baru,
                    'jabatan'        => $request->jabatan,
                    'golongan_akhir' => $request->golongan_akhir,
                    'tamat_gol'      => $request->tamat_gol,
                    'pendidikan'     => $request->pendidikan,
                    'tanggal_lulus'  => $request->tanggal_lulus,
                    'jenis_kelamin'  => $request->jenis_kelamin,
                    'email'          => $request->email,
                ]
            );

            // Buat akun user
            $user = User::create([
                'nip_lama' => $request->nip_lama,
                'username' => $request->username,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'id_role'  => 1, // operator
                'tim_id'   => $request->tim_id,
                'photo'    => $photoPath,
            ]);

        } else {
            $nonBpsTim = Tim::firstOrCreate(['nama_tim' => 'Non-BPS']);

            // Buat data pegawai minimal
            $pegawai = Pegawai::create([
                'nama'          => $request->nama_nonbps,
                'jenis_kelamin' => $request->jenis_kelamin_nonbps,
                'email'         => $request->email_nonbps,
                'nip_lama'      => null,
                'nip_baru'      => null,
                'jabatan'       => null,
                'golongan_akhir'=> null,
                'tamat_gol'     => null,
                'pendidikan'    => null,
                'tanggal_lulus' => null,
            ]);

            // Buat akun user
            $user = User::create([
                'nip_lama' => null,
                'username' => $request->username,
                'email'    => $request->email_nonbps,
                'password' => Hash::make($request->password),
                'id_role'  => 1, // operator
                'tim_id'   => $nonBpsTim->id,
                'photo'    => $photoPath,
            ]);
        }

        event(new Registered($user));
        return redirect()->route('login');
    }
}