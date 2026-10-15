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
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'password.required'  => 'Kata sandi baru wajib diisi.',
            'password.min'       => 'Kata sandi baru minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        // Cek agar kata sandi baru tidak boleh sama persis dengan kata sandi default sistem
        if ($user->role === 'siswa' && $request->password === 'siswasmpsmi') {
            return back()->withErrors(['password' => 'Kata sandi baru tidak boleh sama dengan kata sandi default (siswasmpsmi). Silakan buat kata sandi pribadi Anda.']);
        }

        if ($user->role === 'coach' && $request->password === 'pembinasmpsmi') {
            return back()->withErrors(['password' => 'Kata sandi baru tidak boleh sama dengan kata sandi default (pembinasmpsmi). Silakan buat kata sandi pribadi Anda.']);
        }

        // Simpan kata sandi baru (akan di-hash otomatis oleh casting model User)
        $user->update([
            'password'             => $request->password,
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard')->with('success', 'Kata sandi Anda berhasil diperbarui! Selamat datang di Portal Eksentra.');
    }
}
