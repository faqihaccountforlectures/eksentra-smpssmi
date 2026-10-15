<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil coach / pembina.
     */
    public function index()
    {
        $user = Auth::user()->load('coachProfile');
        return view('coach.profile.index', compact('user'));
    }

    /**
     * Memproses form penyimpanan update profil.
     * Demi keamanan, coach hanya dapat mengubah nomor telepon.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'phone_number' => 'required|string|max:20|regex:/^([0-9\s\-\+\(\)]*)$/',
        ], [
            'phone_number.required' => 'Nomor HP tidak boleh dikosongkan.',
            'phone_number.regex' => 'Format nomor HP tidak valid (hanya boleh angka, spasi, atau tanda tambah).',
            'phone_number.max' => 'Nomor HP terlalu panjang, maksimal 20 karakter.',
        ]);

        $user = Auth::user();
        $profile = $user->coachProfile;

        if (!$profile) {
            return redirect()->back()->with('error', 'Profil pembina / coach tidak ditemukan di database.');
        }

        $profile->update([
            'phone_number' => $validatedData['phone_number']
        ]);

        return redirect()->back()->with('success', 'Data kontak profil Anda berhasil diperbarui!');
    }

    /**
     * Memproses pembaruan kata sandi akun pembina.
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:6', 'confirmed', 'different:current_password'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required'         => 'Kata sandi baru wajib diisi.',
            'password.min'              => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed'        => 'Konfirmasi kata sandi baru tidak sesuai.',
            'password.different'        => 'Kata sandi baru harus berbeda dari kata sandi saat ini.',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak sesuai. Silakan periksa kembali.']);
        }

        if ($request->password === 'pembinasmpsmi') {
            return back()->withErrors(['password' => 'Kata sandi baru tidak boleh sama dengan kata sandi default sistem.']);
        }

        $user->update([
            'password'             => $request->password,
            'must_change_password' => false,
        ]);

        return back()->with('success', 'Kata sandi Anda berhasil diperbarui!');
    }
}
