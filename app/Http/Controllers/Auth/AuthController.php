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

            // Cek jika siswa harus mengubah kata sandi default (berdasarkan flag atau jika login menggunakan password tanggal lahir)
            $birthDate = $studentProfile->birth_date;
            $isDefaultPassword = ($birthDate && $request->password === $birthDate->format('Ymd')) || $request->password === 'siswasmpsmi';

            if ($user->must_change_password || $isDefaultPassword) {
                if (!$user->must_change_password) {
                    $user->update(['must_change_password' => true]);
                }
                return redirect()->route('password.force_change');
            }

            return redirect()->intended('/dashboard');
        }

        // ==========================================
        // 2. LOGIN PEMBINA (USERNAME & PASSWORD)
        // ==========================================
        if ($loginType === 'coach') {
            $request->validate([
                'username' => ['required', 'string'],
                'password' => ['required', 'string'],
            ], [
                'username.required' => 'Username pembina wajib diisi.',
                'password.required' => 'Kata sandi wajib diisi.',
            ]);

            $username = trim($request->username);
            $cleanUsername = str_contains($username, '@') ? explode('@', $username)[0] : $username;

            // Mendukung login dengan username murni atau jika pembina mengetikkan domain
            $user = User::where('role', 'coach')
                ->where(function ($q) use ($username, $cleanUsername) {
                    $q->where('username', $username)
                      ->orWhere('username', $cleanUsername);
                })
                ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return back()->withInput()->with('error', 'Username atau kata sandi pembina tidak cocok. Silakan periksa kembali.')->with('active_tab', 'coach');
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            // Cek jika pembina harus mengubah kata sandi default (berdasarkan flag atau jika login menggunakan password tanggal lahir)
            $birthDate = $user->coachProfile?->birth_date;
            $isDefaultPassword = ($birthDate && $request->password === $birthDate->format('Ymd')) || $request->password === 'pembinasmpsmi';

            if ($user->must_change_password || $isDefaultPassword) {
                if (!$user->must_change_password) {
                    $user->update(['must_change_password' => true]);
                }
                return redirect()->route('password.force_change');
            }

            return redirect()->intended('/dashboard');
        }

        // ==========================================
        // 3. LOGIN ADMINISTRATOR (USERNAME & PASSWORD)
        // ==========================================
        $usernameInput = trim($request->input('username') ?? $request->input('email') ?? '');

        $request->validate([
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (empty($usernameInput)) {
            return back()->withInput()->with('error', 'Username administrator wajib diisi.')->with('active_tab', 'admin');
        }

        // Mendukung username murni atau format email admin
        $cleanUsername = str_contains($usernameInput, '@') ? explode('@', $usernameInput)[0] : $usernameInput;

        $user = User::where('role', 'admin')
            ->where(function ($q) use ($usernameInput, $cleanUsername) {
                $q->where('username', $usernameInput)
                  ->orWhere('username', $cleanUsername);
            })
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withInput()->with('error', 'Username atau kata sandi administrator salah. Silakan coba lagi.')->with('active_tab', 'admin');
    }
}
