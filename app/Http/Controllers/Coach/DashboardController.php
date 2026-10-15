<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\AcademicYear;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk Coach / Pembina Ekstrakurikuler.
     */
    public function index()
    {
        $coachId = Auth::id();
        $user = Auth::user()->load('coachProfile');

        Carbon::setLocale('id');
        $todayName = Carbon::now()->isoFormat('dddd'); 

        $activeYear = AcademicYear::where('is_active', true)->first();

        // 1. Ekstrakurikuler yang dibina oleh coach ini
        $myExtracurriculars = Extracurricular::where('coach_id', $coachId)
            ->withCount([
                'registrations as approved_count' => function ($q) use ($activeYear) {
                    $q->where('status', 'approved');
                    if ($activeYear) {
                        $q->where('academic_year_id', $activeYear->id);
                    }
                },
                'registrations as graded_count' => function ($q) use ($activeYear) {
                    $q->where('status', 'approved')
                      ->whereHas('grades', function ($g) use ($activeYear) {
                          if ($activeYear) {
                              $g->where('academic_year_id', $activeYear->id);
                          }
                      });
                    if ($activeYear) {
                        $q->where('academic_year_id', $activeYear->id);
                    }
                },
                'registrations as pending_count' => function ($q) use ($activeYear) {
                    $q->where('status', 'pending');
                    if ($activeYear) {
                        $q->where('academic_year_id', $activeYear->id);
                    }
                },
            ])
            ->get();

        $ekskulIds = $myExtracurriculars->pluck('id');

        // 2. Statistik Akumulatif
        $totalExtracurriculars = $myExtracurriculars->count();
        $totalMembers = ExtracurricularRegistration::whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'approved')
            ->when($activeYear, function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })
            ->count();

        $totalGraded = ExtracurricularRegistration::whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'approved')
            ->when($activeYear, function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })
            ->whereHas('grades', function ($g) use ($activeYear) {
                if ($activeYear) {
                    $g->where('academic_year_id', $activeYear->id);
                }
            })
            ->count();

        $totalPending = ExtracurricularRegistration::whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'pending')
            ->when($activeYear, function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })
            ->count();

        // 3. Siswa terbaru yang bergabung
        $recentMembers = ExtracurricularRegistration::whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'approved')
            ->when($activeYear, function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })
            ->with(['student.studentProfile', 'extracurricular', 'grades.academicYear'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('coach.dashboard', compact(
            'user',
            'todayName',
            'activeYear',
            'myExtracurriculars',
            'totalExtracurriculars',
            'totalMembers',
            'totalGraded',
            'totalPending',
            'recentMembers'
        ));
    }
}
