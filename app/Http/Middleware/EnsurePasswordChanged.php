<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * Memastikan siswa dan pembina yang masih menggunakan password default
     * atau memiliki flag must_change_password = true segera mengubah password mereka
     * sebelum dapat mengakses fitur portal lainnya.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (in_array($user->role, ['siswa', 'coach'])) {
                $isDefaultPassword = false;

                if ($user->role === 'siswa' && Hash::check('siswasmpsmi', $user->password)) {
                    $isDefaultPassword = true;
                } elseif ($user->role === 'coach' && Hash::check('pembinasmpsmi', $user->password)) {
                    $isDefaultPassword = true;
                }

                if ($user->must_change_password || $isDefaultPassword) {
                    // Izinkan akses khusus untuk rute form ganti password dan proses logout
                    if (!$request->routeIs('password.force_change', 'password.force_change.update', 'logout')) {
                        return redirect()->route('password.force_change')
                            ->with('warning', 'Demi keamanan akun, Anda wajib mengubah kata sandi default sebelum melanjutkan.');
                    }
                } else {
                    // Jika sudah ganti password dan mencoba mengakses halaman force change password
                    if ($request->routeIs('password.force_change', 'password.force_change.update')) {
                        return redirect()->route('dashboard');
                    }
                }
            }
        }

        return $next($request);
    }
}
