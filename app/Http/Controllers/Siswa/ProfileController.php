<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentProfile;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil siswa.
     */
    public function index()
    {
        // Ambil data user yang sedang login beserta profil siswanya
        $user = Auth::user();
        
        // Akses relasi studentProfile, karena yang login dipastikan punya role siswa
        // relasi ini akan terisi data profilnya
        $profile = $user->studentProfile;

        // Jika karena alasan tertentu data profilnya tidak ada (seharusnya otomatis dibuat saat pendaftaran),
        // kita tangani agar tidak error dengan membuat instance kosong
        if (!$profile) {
            $profile = new StudentProfile();
            $profile->user_id = $user->id;
            // kolom lainnya akan dibiarkan kosong/null
        }

        return view('siswa.profile.index', compact('user', 'profile'));
    }

    /**
     * Memperbarui informasi kontak siswa.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->studentProfile;

        // Validasi input khusus untuk nomor HP saja
        $request->validate([
            'phone_number' => 'nullable|string|max:20',
            'parent_phone' => 'nullable|string|max:20',
        ], [
            'phone_number.max' => 'Nomor HP tidak boleh lebih dari 20 karakter.',
            'parent_phone.max' => 'Nomor HP Orang Tua tidak boleh lebih dari 20 karakter.',
        ]);

        // Cek jika profile belum ada di database, kita buatkan instance baru
        if (!$profile) {
            $profile = new StudentProfile();
            $profile->user_id = $user->id;
        }

        // Update data kontak
        $profile->phone_number = $request->phone_number;
        $profile->parent_phone = $request->parent_phone;
        
        // Simpan perubahan ke database
        $profile->save();

        return redirect()->back()->with('success', 'Informasi kontak berhasil diperbarui!');
    }

    /**
     * Memperbarui kata sandi akun siswa.
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

        if ($request->password === 'siswasmpsmi') {
            return back()->withErrors(['password' => 'Kata sandi baru tidak boleh sama dengan kata sandi default sistem.']);
        }

        $user->update([
            'password'             => $request->password,
            'must_change_password' => false,
        ]);

        return back()->with('success', 'Kata sandi Anda berhasil diperbarui!');
    }
}
