<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ForcePasswordChangeController extends Controller
{
    /**
     * Menampilkan formulir wajib ganti kata sandi default bagi siswa dan pembina.
     */
    public function show()
    {
        $user = Auth::user();

        // Admin tidak diwajibkan melalui alur ini
        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }

        return view('auth.force-change-password', compact('user'));
    }

    /**
     * Memproses penyimpanan kata sandi baru.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'password.required'  => 'Kata sandi baru wajib diisi.',
            'password.min'       => 'Kata sandi baru minimal harus 8 karakter.',
            'password.letters'   => 'Kata sandi baru harus mengandung setidaknya satu huruf.',
            'password.numbers'   => 'Kata sandi baru harus mengandung setidaknya satu angka.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        // Cek agar kata sandi baru tidak boleh sama persis dengan tanggal lahir pengguna (YYYYMMDD)
        $birthDate = null;
        if ($user->role === 'siswa') {
            $birthDate = $user->studentProfile?->birth_date;
        } elseif ($user->role === 'coach') {
            $birthDate = $user->coachProfile?->birth_date;
        }

        if ($birthDate) {
            $birthDatePass = \Carbon\Carbon::parse($birthDate)->format('Ymd');
            if ($request->password === $birthDatePass) {
                return back()->withErrors(['password' => "Kata sandi baru tidak boleh sama dengan tanggal lahir Anda ({$birthDatePass}). Silakan buat kata sandi baru yang berbeda dan aman."]);
            }
        }

        // Cegah kata sandi default statis lama
        if (in_array($request->password, ['siswasmpsmi', 'pembinasmpsmi'])) {
            return back()->withErrors(['password' => 'Kata sandi baru tidak boleh menggunakan kata sandi default sistem. Silakan buat kata sandi pribadi Anda.']);
        }

        // Simpan kata sandi baru (akan di-hash otomatis oleh casting model User)
        $user->update([
            'password'             => $request->password,
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard')->with('success', 'Kata sandi Anda berhasil diperbarui! Selamat datang di Portal Eksentra.');
    }
}
