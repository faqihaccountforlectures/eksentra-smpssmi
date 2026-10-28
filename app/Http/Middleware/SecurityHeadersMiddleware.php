<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and attach HTTP security headers.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 1. Mencegah Clickjacking (halaman disematkan dalam <iframe> oleh situs phising)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 2. Mencegah MIME Type Sniffing (browser mengeksekusi file gambar/bukti transfer sebagai script)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 3. Filter proteksi XSS bawaan untuk browser versi lama
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // 4. Mencegah kebocoran parameter URL sensitif saat pengguna mengklik link keluar
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 5. Membatasi akses sensor & fitur perangkat yang tidak digunakan dalam aplikasi
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        // 6. Strict-Transport-Security (HSTS) jika diakses melalui koneksi HTTPS aman
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
