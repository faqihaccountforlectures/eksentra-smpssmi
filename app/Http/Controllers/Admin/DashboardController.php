<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ExtracurricularRegistration;
use App\Models\Payment;
use App\Models\Extracurricular;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama admin dengan data statistik dinamis.
     */
    public function index()
    {
        // 1. Hitung total siswa aktif dan alumni
        $totalSiswa = User::where('role', 'siswa')
            ->whereHas('studentProfile', fn($q) => $q->where('status', 'aktif'))
            ->count();
        $totalAlumni = User::where('role', 'siswa')
            ->whereHas('studentProfile', fn($q) => $q->where('status', 'lulus'))
            ->count();

        // 2. Hitung total coach (pembina ekstrakurikuler)
        $totalCoach = User::where('role', 'coach')->count();

        // 3. Hitung pendaftaran ekstrakurikuler yang perlu verifikasi (pending)
        $pendingRegistrations = ExtracurricularRegistration::where('status', 'pending')->count();

        // 4. Hitung pembayaran ekstrakurikuler yang perlu verifikasi (pending)
        $pendingPayments = Payment::where('payment_status', 'pending')->count();

        // 5. Hitung total pemasukan iuran bulan ini yang sudah diverifikasi (berdasarkan periode bulan & tahun iuran)
        $monthsName = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $currentMonthName = $monthsName[now()->month];

        $pemasukanBulanIni = Payment::where('payment_status', 'verified')
            ->where('month', $currentMonthName)
            ->where('year', now()->year)
            ->sum('total_amount');

        // 6. Data untuk Chart.js (Grafik Ekskul Terfavorit)
        $extracurriculars = Extracurricular::withCount(['registrations' => function ($query) {
            $query->where('status', 'approved');
        }])->get();
        
        $chartLabels = $extracurriculars->pluck('name');
        $chartData = $extracurriculars->pluck('registrations_count');

        // 7. Ambil 5 setoran pembayaran terbaru
        $recentPayments = Payment::with(['registration' => fn($q) => $q->withTrashed()->with(['student.studentProfile', 'extracurricular'])])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalAlumni',
            'totalCoach',
            'pendingRegistrations',
            'pendingPayments',
            'pemasukanBulanIni',
            'chartLabels',
            'chartData',
            'recentPayments'
        ));
    }
}
