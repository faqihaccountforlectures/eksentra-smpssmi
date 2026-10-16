<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ClassEnrollment;
use App\Models\ExtracurricularRegistration;
use App\Models\Payment;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama khusus untuk siswa pada Portal Ekstrakurikuler.
     */
    public function index()
    {
        // 1. Ambil data user yang sedang login beserta profil siswanya
        $user = Auth::user()->load('studentProfile');

        // 2. Ambil data kelas siswa saat ini (jika ada)
        $enrollment = ClassEnrollment::with('classRoom')
            ->where('student_id', $user->id)
            ->first();

        $className = $enrollment && $enrollment->classRoom ? $enrollment->classRoom->name : 'Belum Ada Kelas';

        $activeYear = AcademicYear::where('is_active', true)->first();

        // 3. Ambil pendaftaran ekskul siswa untuk tahun ajaran aktif saja
        $allRegistrations = ExtracurricularRegistration::with(['extracurricular.coach.coachProfile', 'grades.academicYear', 'academicYear'])
            ->where('student_id', $user->id)
            ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->get();

        $approvedRegistrations = $allRegistrations->where('status', 'approved');
        $pendingRegistrations = $allRegistrations->where('status', 'pending');

        // 4. Cek apakah ada tagihan bulanan ekskul yang tertunggak (Belum dibayar)
        $hasUnpaidBills = false;
        $payments = Payment::whereHas('registration', function ($q) use ($user) {
            $q->withTrashed()->where('student_id', $user->id);
        })->get();

        $monthsName = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $currentDate = now()->startOfMonth();

        foreach ($approvedRegistrations as $reg) {
            if (!$reg->extracurricular || $reg->extracurricular->fee <= 0) continue; // Skip ekskul gratis

            // Rentang tagihan mengikuti tahun ajaran (Juli – Juni)
            $yearModel = $reg->academicYear ?? $activeYear;
            $yearStart = $yearModel ? $yearModel->startDate() : $reg->created_at->copy()->startOfMonth();
            $yearEnd   = $yearModel ? $yearModel->endDate()->startOfMonth() : $currentDate->copy();

            $billStart    = $reg->created_at->startOfMonth()->max($yearStart);
            $billEnd      = $currentDate->min($yearEnd);
            $iteratorDate = $billStart->copy();

            while ($iteratorDate->lte($billEnd)) {
                $monthStr = $monthsName[$iteratorDate->month];
                $year     = $iteratorDate->year;

                $hasPaid = $payments->where('registration_id', $reg->id)
                                    ->where('month', $monthStr)
                                    ->where('year', $year)
                                    ->whereIn('payment_status', ['pending', 'verified'])
                                    ->first();

                if (!$hasPaid) {
                    $hasUnpaidBills = true;
                    break 2;
                }
                $iteratorDate->addMonth();
            }
        }

        // 5. Pembayaran terbaru (termasuk ekskul yang pendaftarannya sudah keluar/soft-deleted)
        $recentPayments = Payment::whereHas('registration', function ($q) use ($user) {
                $q->withTrashed()->where('student_id', $user->id);
            })
            ->with(['registration' => fn($q) => $q->withTrashed()->with('extracurricular')])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('siswa.dashboard', compact(
            'user', 
            'className', 
            'activeYear',
            'approvedRegistrations',
            'pendingRegistrations',
            'allRegistrations',
            'hasUnpaidBills',
            'recentPayments'
        ));
    }
}
