<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Rate limiter cerdas untuk autentikasi login (Kombinasi Akun + IP)
        // Mencegah seluruh lab komputer / WiFi sekolah terkunci ketika 1 siswa salah password berkali-kali.
        RateLimiter::for('login', function (Request $request) {
            $identifier = $request->input('nis') 
                ?? $request->input('username') 
                ?? $request->input('email') 
                ?? 'guest';

            $key = Str::transliterate(Str::lower(trim($identifier))) . '|' . $request->ip();

            return Limit::perMinute(5)->by($key)->response(function (Request $request, array $headers) {
                $seconds = $headers['Retry-After'] ?? 60;
                return back()
                    ->withInput()
                    ->with('error', "Terlalu banyak percobaan masuk yang gagal. Silakan tunggu {$seconds} detik lagi sebelum mencoba kembali.")
                    ->with('active_tab', $request->input('login_type', 'siswa'));
            });
        });
    }
}
