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
}
