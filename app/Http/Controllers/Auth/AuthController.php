<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Controller untuk menangani proses otentikasi login pengguna:
 * - Siswa: Login menggunakan NIS dan Password.
 * - Pembina: Login menggunakan Username / Email (@smpsmi.com) dan Password.
 * - Administrator: Login menggunakan Email dan Password.
 */
class AuthController extends Controller
{
    /**
     * Memproses otentikasi login pengguna ke sistem berdasarkan peran.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        $loginType = $request->input('login_type');

        // Deteksi otomatis jika peran login tidak terisi eksplisit
        if (!$loginType) {
            if ($request->filled('nis')) {
                $loginType = 'siswa';
            } elseif ($request->filled('username')) {
                $loginType = 'coach';
            } else {
                $loginType = 'admin';
            }
        }

        // ==========================================
        // 1. LOGIN SISWA (NIS & PASSWORD)
        // ==========================================
        if ($loginType === 'siswa') {
            $request->validate([
                'nis'      => ['required', 'string'],
                'password' => ['required', 'string'],
            ], [
                'nis.required'      => 'Nomor Induk Siswa (NIS) wajib diisi.',
                'password.required' => 'Kata sandi wajib diisi.',
            ]);

            $nis = trim($request->nis);
            $studentProfile = StudentProfile::where('nis', $nis)->first();

            if (!$studentProfile || !$studentProfile->user) {
                return back()->withInput()->with('error', 'NIS tersebut tidak terdaftar di sistem.')->with('active_tab', 'siswa');
            }

            if ($studentProfile->status === 'keluar') {
                return back()->withInput()->with('error', 'Akun siswa ini telah dinonaktifkan (keluar).')->with('active_tab', 'siswa');
            }

            $user = $studentProfile->user;

            if (!Hash::check($request->password, $user->password)) {
                return back()->withInput()->with('error', 'NIS atau kata sandi siswa tidak cocok. Silakan periksa kembali.')->with('active_tab', 'siswa');
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            // Cek jika siswa harus mengubah kata sandi default
            if ($user->must_change_password || Hash::check('siswasmpsmi', $user->password)) {
                return redirect()->route('password.force_change');
            }

            return redirect()->intended('/dashboard');
        }

        // ==========================================
        // 2. LOGIN PEMBINA (USERNAME / EMAIL & PASSWORD)
        // ==========================================
        if ($loginType === 'coach') {
            $request->validate([
                'username' => ['required', 'string'],
                'password' => ['required', 'string'],
            ], [
                'username.required' => 'Username atau email pembina wajib diisi.',
                'password.required' => 'Kata sandi wajib diisi.',
            ]);

            $username = trim($request->username);

            // Mendukung login dengan email penuh (misal: nama@smpsmi.com) atau username awal saja
            $user = User::where('role', 'coach')
                ->where(function ($q) use ($username) {
                    $q->where('email', $username)
                      ->orWhere('email', $username . '@smpsmi.com');
                    if (!str_contains($username, '@')) {
                        $q->orWhere('email', 'like', $username . '@%');
                    }
                })
                ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return back()->withInput()->with('error', 'Username atau kata sandi pembina tidak cocok. Silakan periksa kembali.')->with('active_tab', 'coach');
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            // Cek jika pembina harus mengubah kata sandi default
            if ($user->must_change_password || Hash::check('pembinasmpsmi', $user->password)) {
                return redirect()->route('password.force_change');
            }

            return redirect()->intended('/dashboard');
        }

        // ==========================================
        // 3. LOGIN ADMINISTRATOR (EMAIL & PASSWORD)
        // ==========================================
        $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Email administrator wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $credentials = [
            'email'    => trim($request->email),
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withInput()->with('error', 'Akun ini bukan Administrator. Silakan masuk melalui tab Siswa atau Pembina.')->with('active_tab', 'admin');
            }

            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withInput()->with('error', 'Email atau kata sandi administrator salah. Silakan coba lagi.')->with('active_tab', 'admin');
    }
}
