<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\ExtracurricularRegistration;
use App\Models\Payment;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.tailwind');
        Paginator::useTailwind();

        // Bagikan data notifikasi verifikasi ke layout admin secara efisien
        View::composer('layouts.app', function ($view) {
            if (Auth::check() && Auth::user()->role === 'admin') {
                try {
                    $pendingRegistrationsCount = ExtracurricularRegistration::where('status', 'pending')->count();
                    $pendingPaymentsCount = Payment::where('payment_status', 'pending')->count();
                    $totalPendingCount = $pendingRegistrationsCount + $pendingPaymentsCount;

                    $view->with([
                        'pendingRegistrationsCount' => $pendingRegistrationsCount,
                        'pendingPaymentsCount' => $pendingPaymentsCount,
                        'totalPendingCount' => $totalPendingCount,
                    ]);
                } catch (\Exception $e) {
                    $view->with([
                        'pendingRegistrationsCount' => 0,
                        'pendingPaymentsCount' => 0,
                        'totalPendingCount' => 0,
                    ]);
                }
            }
        });
    }
}
