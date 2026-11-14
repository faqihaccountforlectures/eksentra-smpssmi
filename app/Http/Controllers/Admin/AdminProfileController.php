<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    /**
     * Menampilkan halaman utama Profil & Pengaturan Akun Admin.
     * Memuat data admin yang sedang login untuk ditampilkan pada form.
     */
    public function index()
    {
        // Mengambil instance data user/admin yang sedang aktif login
        $user = Auth::user();

        // Mengembalikan view khusus profil admin dengan membawa data user
        return view('admin.profile.index', compact('user'));
    }

    /**
     * Memproses pembaruan data profil admin (Username).
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $oldUsername = $user->username;
        $usernameInput = trim($request->input('username') ?? '');
        $request->merge(['username' => $usernameInput]);

        $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username,' . $user->id],
        ], [
            'username.required'   => 'Username wajib diisi.',
            'username.min'        => 'Username minimal harus 3 karakter.',
            'username.max'        => 'Username maksimal 50 karakter.',
            'username.alpha_dash' => 'Username hanya boleh berupa kombinasi huruf, angka, tanda strip (-), atau garis bawah (_).',
            'username.unique'     => 'Username ini sudah digunakan oleh akun lain.',
        ]);

        if ($oldUsername !== $usernameInput) {
            $user->update([
                'username' => $usernameInput,
            ]);

            ActivityLog::record(
                'ADMIN_USERNAME_CHANGED',
                "Admin memperbarui username login dari '{$oldUsername}' menjadi '{$usernameInput}'."
            );
        }

        return back()->with('success', 'Username akun admin berhasil diperbarui.');
    }

    /**
     * Memproses pembaruan password admin.
     * Menghentikan sesi login di perangkat lain secara otomatis demi keamanan serah terima.
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi form penggantian password: kata sandi saat ini wajib diisi demi keamanan
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'confirmed', 'different:current_password', Password::min(8)->letters()->numbers()],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal harus 8 karakter.',
            'password.letters'          => 'Password baru harus mengandung setidaknya satu huruf.',
            'password.numbers'          => 'Password baru harus mengandung setidaknya satu angka.',
            'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
            'password.different'        => 'Password baru harus berbeda dari password saat ini.',
        ]);

        // Verifikasi kebenaran password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini yang Anda ketikkan tidak sesuai. Silakan periksa kembali.']);
        }

        // 1. Update password baru ke database (akan di-hash otomatis oleh model User $casts)
        $user->update([
            'password' => $request->password,
        ]);

        // Catat ke Audit Trail / Log Aktivitas
        ActivityLog::record(
            'ADMIN_PASSWORD_CHANGED',
            "Admin ({$user->username}) memperbarui kata sandi akun dan menghentikan seluruh sesi aktif lain."
        );

        // 2. FITUR KEAMANAN UTAMA: Purge Sesi Login di Perangkat Lain!
        // Ketika password diganti (misal saat serah terima admin baru),
        // fungsi ini akan menghancurkan cookie/sesi aktif admin di laptop/HP lain secara otomatis.
        Auth::logoutOtherDevices($request->password);

        return back()->with('success', 'Password akun admin berhasil diubah! Semua sesi aktif admin di perangkat lain telah dihentikan demi keamanan.');
    }
}
